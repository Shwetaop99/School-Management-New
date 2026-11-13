<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('author', 'like', '%' . $request->search . '%')
                    ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $books = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $totalBooks = Book::sum('quantity');

        $availableBooks = Book::sum('available_quantity');

        $unavailableBooks = Book::where('status', 'Unavailable')
            ->count();

        $categories = Book::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.library.books.index', compact(
            'books',
            'totalBooks',
            'availableBooks',
            'unavailableBooks',
            'categories'
        ));
    }


    public function create()
    {
        return view('admin.library.books.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255|unique:books,isbn',
            'category' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'shelf_number' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'cover_image' => 'nullable|url|max:2048',
            'description' => 'nullable|string',
            'status' => 'required|in:Available,Unavailable',
        ]);

        // Available quantity starts equal to total quantity.
        $validated['available_quantity'] = $validated['quantity'];

        Book::create($validated);

        return redirect()
            ->route('admin.library.books.index')
            ->with('success', 'Book added successfully.');
    }


    public function show(Book $book)
    {
        return view('admin.library.books.show', compact('book'));
    }


    public function edit(Book $book)
    {
        return view('admin.library.books.edit', compact('book'));
    }


    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255|unique:books,isbn,' . $book->id,
            'category' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'shelf_number' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'cover_image' => 'nullable|url|max:2048',
            'description' => 'nullable|string',
            'status' => 'required|in:Available,Unavailable',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Keep Track Of Issued Copies
        |--------------------------------------------------------------------------
        */

        $issuedCopies = $book->quantity - $book->available_quantity;

        /*
        |--------------------------------------------------------------------------
        | Prevent Quantity From Going Below Issued Copies
        |--------------------------------------------------------------------------
        */

        if ($validated['quantity'] < $issuedCopies) {
            return back()
                ->withErrors([
                    'quantity' => "Quantity cannot be less than {$issuedCopies} issued copies.",
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Recalculate Available Quantity
        |--------------------------------------------------------------------------
        */

        $validated['available_quantity'] =
            $validated['quantity'] - $issuedCopies;

        $book->update($validated);

        return redirect()
            ->route('admin.library.books.show', $book)
            ->with('success', 'Book updated successfully.');
    }


    public function destroy(Book $book)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Cover From Cloudinary
        |--------------------------------------------------------------------------
        */

        if ($book->cover_image) {
            try {
                $urlPath = parse_url(
                    $book->cover_image,
                    PHP_URL_PATH
                );

                if ($urlPath) {
                    $parts = explode('/upload/', $urlPath, 2);

                    if (count($parts) === 2) {
                        $publicIdWithExtension = $parts[1];

                        // Remove version if present.
                        $publicIdWithExtension = preg_replace(
                            '/^v\d+\//',
                            '',
                            $publicIdWithExtension
                        );

                        // Remove file extension.
                        $directory = pathinfo(
                            $publicIdWithExtension,
                            PATHINFO_DIRNAME
                        );

                        $filename = pathinfo(
                            $publicIdWithExtension,
                            PATHINFO_FILENAME
                        );

                        $publicId = $directory === '.'
                            ? $filename
                            : $directory . '/' . $filename;

                        /*
                        |--------------------------------------------------------------------------
                        | Cloudinary Delete Request
                        |--------------------------------------------------------------------------
                        */

                        $timestamp = time();

                        $signature = sha1(
                            'public_id=' . $publicId .
                            '&timestamp=' . $timestamp .
                            config('services.cloudinary.api_secret')
                        );

                        Http::asForm()->post(
                            'https://api.cloudinary.com/v1_1/' .
                            config('services.cloudinary.cloud_name') .
                            '/image/destroy',
                            [
                                'public_id' => $publicId,
                                'timestamp' => $timestamp,
                                'api_key' => config('services.cloudinary.api_key'),
                                'signature' => $signature,
                            ]
                        );
                    }
                }
            } catch (\Throwable $e) {
                Log::error(
                    'Cloudinary cover deletion failed: ' .
                    $e->getMessage()
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Book From Database
        |--------------------------------------------------------------------------
        */

        $book->delete();

        return redirect()
            ->route('admin.library.books.index')
            ->with('success', 'Book deleted successfully.');
    }
}
