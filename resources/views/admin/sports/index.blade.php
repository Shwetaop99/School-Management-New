@extends('layouts.app')

@section('title', 'Sports | Admin')

@section('content')

<div class="sports-page">

    <div class="container-fluid py-4">

        {{-- =========================================================
            PAGE HEADER
        ========================================================== --}}
        <div class="sports-header">

            <div class="sports-header-left">

                <div class="sports-title-icon">
                    <i class="fas fa-futbol"></i>
                </div>

                <div>
                    <h2>Sports Management</h2>

                    <p>
                        Manage games, events, achievements and sports equipment.
                    </p>
                </div>

            </div>

            <div class="sports-header-actions">

                <button type="button" class="sports-outline-btn">
                    <i class="fas fa-chart-column"></i>
                    Reports
                </button>

                <button type="button" class="sports-primary-btn">
                    <i class="fas fa-plus"></i>
                    Add Game / Event
                </button>

            </div>

        </div>


        {{-- =========================================================
            STATISTICS
            4 CARDS IN ONE ROW
        ========================================================== --}}
        <div class="sports-stats-row">

            {{-- TOTAL GAMES --}}
            <div class="sports-stat-col">

                <div class="sports-stat-card blue-card">

                    <div class="sports-stat-icon">
                        <i class="fas fa-futbol"></i>
                    </div>

                    <div class="sports-stat-info">

                        <span>Total Games</span>

                        <h3>0</h3>

                        <small>
                            <i class="fas fa-calendar-days"></i>
                            Games & Events
                        </small>

                    </div>

                </div>

            </div>


            {{-- ACHIEVEMENTS --}}
            <div class="sports-stat-col">

                <div class="sports-stat-card orange-card">

                    <div class="sports-stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>

                    <div class="sports-stat-info">

                        <span>Achievements</span>

                        <h3>0</h3>

                        <small>
                            <i class="fas fa-medal"></i>
                            Student Achievements
                        </small>

                    </div>

                </div>

            </div>


            {{-- PARTICIPANTS --}}
            <div class="sports-stat-col">

                <div class="sports-stat-card red-card">

                    <div class="sports-stat-icon">
                        <i class="fas fa-users"></i>
                    </div>

                    <div class="sports-stat-info">

                        <span>Participants</span>

                        <h3>0</h3>

                        <small>
                            <i class="fas fa-user-group"></i>
                            Sports Participants
                        </small>

                    </div>

                </div>

            </div>


            {{-- EQUIPMENT --}}
            <div class="sports-stat-col">

                <div class="sports-stat-card cyan-card">

                    <div class="sports-stat-icon">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>

                    <div class="sports-stat-info">

                        <span>Equipment</span>

                        <h3>0</h3>

                        <small>
                            <i class="fas fa-warehouse"></i>
                            Sports Inventory
                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            RECENT SPORTS ACTIVITY
        ========================================================== --}}
        <div class="sports-panel-card">

            {{-- PANEL HEADER --}}
            <div class="panel-header">

                <div class="panel-heading">

                    <div class="panel-heading-icon">
                        <i class="fas fa-clock-rotate-left"></i>
                    </div>

                    <div>

                        <h5>Recent Sports Activity</h5>

                        <p>
                            Latest games, achievements and equipment updates.
                        </p>

                    </div>

                </div>


                <div class="panel-header-actions">

                    <button type="button" class="activity-filter-btn">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>

                    <button type="button" class="activity-refresh-btn">
                        <i class="fas fa-rotate-right"></i>
                    </button>

                </div>

            </div>


            {{-- =====================================================
                ACTIVITY TABLE
            ====================================================== --}}
            <div class="activity-table-wrapper">

                <table class="sports-activity-table">

                    <thead>

                        <tr>

                            <th width="70">
                                #
                            </th>

                            <th>
                                Activity
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Participants
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="90">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        {{-- EMPTY STATE --}}
                        <tr>

                            <td colspan="7">

                                <div class="empty-activity">

                                    <div class="empty-icon">
                                        <i class="fas fa-futbol"></i>
                                    </div>

                                    <h6>
                                        No Sports Activity Yet
                                    </h6>

                                    <p>
                                        Recent games, achievements and equipment
                                        activities will appear here.
                                    </p>

                                    <button
                                        type="button"
                                        class="empty-add-btn"
                                    >
                                        <i class="fas fa-plus"></i>
                                        Add First Activity
                                    </button>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- =====================================================
                PAGINATION FOOTER
            ====================================================== --}}
            <div class="activity-footer">

                <div class="activity-showing">

                    Showing
                    <strong>0</strong>
                    to
                    <strong>0</strong>
                    of
                    <strong>0</strong>
                    activities

                </div>


                <div class="activity-pagination">

                    <button
                        type="button"
                        class="pagination-btn disabled"
                        disabled
                    >
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <button
                        type="button"
                        class="pagination-number active"
                    >
                        1
                    </button>

                    <button
                        type="button"
                        class="pagination-btn disabled"
                        disabled
                    >
                        <i class="fas fa-chevron-right"></i>
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>


<style>

/* =========================================================
   SPORTS PAGE
========================================================= */

.sports-page {

    background: #f6f8fb;

    min-height: calc(100vh - 60px);

    color: #172033;

}


/* =========================================================
   PAGE HEADER
========================================================= */

.sports-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 22px;

}


.sports-header-left {

    display: flex;

    align-items: center;

    gap: 13px;

}


.sports-title-icon {

    width: 46px;

    height: 46px;

    min-width: 46px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 12px;

    background:
        linear-gradient(
            135deg,
            #147cf5,
            #1268ca
        );

    color: #fff;

    font-size: 19px;

    box-shadow:
        0 6px 16px rgba(20,124,245,.16);

}


.sports-header h2 {

    margin: 0 0 4px;

    color: #172033;

    font-size: 23px;

    line-height: 1.2;

    font-weight: 750;

    letter-spacing: -.35px;

}


.sports-header p {

    margin: 0;

    color: #7a8799;

    font-size: 12px;

    line-height: 1.5;

}


/* =========================================================
   HEADER ACTIONS
========================================================= */

.sports-header-actions {

    display: flex;

    align-items: center;

    gap: 8px;

}


.sports-primary-btn,
.sports-outline-btn {

    height: 38px;

    padding: 0 14px;

    border-radius: 8px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    font-size: 11.5px;

    font-weight: 650;

    cursor: pointer;

    transition:
        transform .2s ease,
        box-shadow .2s ease,
        background .2s ease,
        border-color .2s ease;

}


.sports-primary-btn {

    border: 1px solid #147cf5;

    background: #147cf5;

    color: #fff;

    box-shadow:
        0 5px 12px rgba(20,124,245,.14);

}


.sports-primary-btn:hover {

    background: #1268ca;

    border-color: #1268ca;

    color: #fff;

    transform: translateY(-1px);

    box-shadow:
        0 8px 16px rgba(20,124,245,.20);

}


.sports-outline-btn {

    border: 1px solid #e4e9f0;

    background: #fff;

    color: #64748b;

}


.sports-outline-btn:hover {

    background: #f8fbff;

    border-color: #cfe0f5;

    color: #147cf5;

    transform: translateY(-1px);

}


/* =========================================================
   STATISTICS
   FOUR CARDS IN ONE ROW
========================================================= */

.sports-stats-row {

    width: 100%;

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 14px;

    margin-bottom: 23px;

}


.sports-stat-col {

    min-width: 0;

}


/* =========================================================
   STAT CARD
========================================================= */

.sports-stat-card {

    position: relative;

    overflow: hidden;

    width: 100%;

    min-height: 126px;

    padding: 19px 20px;

    border-radius: 14px;

    border: 0;

    display: flex;

    align-items: center;

    gap: 14px;

    color: #fff;

    transition:
        transform .22s ease,
        box-shadow .22s ease;

}


.sports-stat-card::before {

    content: "";

    position: absolute;

    width: 62px;

    height: 62px;

    right: 17px;

    top: -28px;

    border-radius: 50%;

    background: rgba(255,255,255,.06);

    pointer-events: none;

}


.sports-stat-card::after {

    content: "";

    position: absolute;

    width: 105px;

    height: 105px;

    right: -32px;

    bottom: -45px;

    border-radius: 50%;

    background: rgba(255,255,255,.08);

    pointer-events: none;

}


/* =========================================================
   CARD COLORS
========================================================= */

.blue-card {

    background:
        linear-gradient(
            135deg,
            #147cf5 0%,
            #1268ca 100%
        );

    box-shadow:
        0 7px 18px rgba(20,124,245,.17);

}


.orange-card {

    background:
        linear-gradient(
            135deg,
            #ffb238 0%,
            #ff9d1c 100%
        );

    box-shadow:
        0 7px 18px rgba(255,178,56,.18);

}


.red-card {

    background:
        linear-gradient(
            135deg,
            #ff6d61 0%,
            #f65343 100%
        );

    box-shadow:
        0 7px 18px rgba(246,83,67,.17);

}


.cyan-card {

    background:
        linear-gradient(
            135deg,
            #2bcfe8 0%,
            #18b5d5 100%
        );

    box-shadow:
        0 7px 18px rgba(24,181,213,.17);

}


/* =========================================================
   STAT HOVER
========================================================= */

.sports-stat-card:hover {

    transform: translateY(-3px);

    box-shadow:
        0 12px 25px rgba(15,23,42,.14);

}


/* =========================================================
   STAT ICON
========================================================= */

.sports-stat-icon {

    position: relative;

    z-index: 2;

    width: 49px;

    height: 49px;

    min-width: 49px;

    border-radius: 12px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: rgba(255,255,255,.17);

    border: 1px solid rgba(255,255,255,.18);

    color: #fff;

    font-size: 18px;

    box-shadow:
        0 4px 12px rgba(0,0,0,.08);

}


/* =========================================================
   STAT INFO
========================================================= */

.sports-stat-info {

    position: relative;

    z-index: 2;

    min-width: 0;

}


.sports-stat-info > span {

    display: block;

    margin-bottom: 5px;

    color: rgba(255,255,255,.88);

    font-size: 11px;

    font-weight: 600;

    white-space: nowrap;

}


.sports-stat-info h3 {

    margin: 0 0 5px;

    color: #fff;

    font-size: 25px;

    line-height: 1;

    font-weight: 800;

}


.sports-stat-info small {

    display: flex;

    align-items: center;

    gap: 5px;

    color: rgba(255,255,255,.78);

    font-size: 9.5px;

    white-space: nowrap;

}


/* =========================================================
   RECENT SPORTS ACTIVITY PANEL
========================================================= */

.sports-panel-card {

    overflow: hidden;

    background: #fff;

    border: 1px solid #edf0f5;

    border-radius: 14px;

    box-shadow:
        0 4px 16px rgba(15,23,42,.045);

}


/* =========================================================
   PANEL HEADER
========================================================= */

.panel-header {

    min-height: 70px;

    padding: 15px 19px;

    border-bottom: 1px solid #edf0f5;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

}


.panel-heading {

    display: flex;

    align-items: center;

    gap: 11px;

}


.panel-heading-icon {

    width: 38px;

    height: 38px;

    min-width: 38px;

    border-radius: 10px;

    background: #edf5ff;

    color: #147cf5;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 13px;

}


.panel-header h5 {

    margin: 0 0 3px;

    color: #172033;

    font-size: 15px;

    font-weight: 700;

}


.panel-header p {

    margin: 0;

    color: #7a8799;

    font-size: 10.5px;

}


/* =========================================================
   PANEL ACTIONS
========================================================= */

.panel-header-actions {

    display: flex;

    align-items: center;

    gap: 7px;

}


.activity-filter-btn {

    height: 34px;

    padding: 0 11px;

    border-radius: 8px;

    border: 1px solid #e5eaf1;

    background: #fff;

    color: #64748b;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    font-size: 10.5px;

    font-weight: 600;

    cursor: pointer;

    transition:
        background .2s ease,
        color .2s ease,
        border-color .2s ease;

}


.activity-filter-btn:hover {

    background: #f4f8fd;

    color: #147cf5;

    border-color: #d5e4f7;

}


.activity-refresh-btn {

    width: 34px;

    height: 34px;

    border-radius: 8px;

    border: 1px solid #e5eaf1;

    background: #fff;

    color: #64748b;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 11px;

    cursor: pointer;

    transition:
        background .2s ease,
        color .2s ease,
        transform .2s ease;

}


.activity-refresh-btn:hover {

    background: #edf5ff;

    color: #147cf5;

    transform: rotate(25deg);

}


/* =========================================================
   ACTIVITY TABLE
========================================================= */

.activity-table-wrapper {

    width: 100%;

    overflow-x: auto;

}


.sports-activity-table {

    width: 100%;

    border-collapse: collapse;

    min-width: 850px;

}


.sports-activity-table thead {

    background: #fafbfd;

}


.sports-activity-table th {

    height: 48px;

    padding: 0 18px;

    border-bottom: 1px solid #edf0f5;

    color: #7a8799;

    text-align: left;

    font-size: 9.5px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .45px;

    white-space: nowrap;

}


.sports-activity-table td {

    padding: 0 18px;

    border-bottom: 1px solid #f0f2f6;

    color: #475569;

    font-size: 11px;

}


/* =========================================================
   EMPTY ACTIVITY
========================================================= */

.empty-activity {

    min-height: 190px;

    margin: 18px 0;

    border: 1px dashed #dce4ee;

    border-radius: 11px;

    background: #fafbfd;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-direction: column;

    text-align: center;

    padding: 25px;

}


.empty-icon {

    width: 45px;

    height: 45px;

    margin-bottom: 10px;

    border-radius: 12px;

    background: #edf5ff;

    color: #147cf5;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 17px;

}


.empty-activity h6 {

    margin: 0 0 5px;

    color: #334155;

    font-size: 12.5px;

    font-weight: 700;

}


.empty-activity p {

    margin: 0 0 13px;

    color: #9aa6b6;

    font-size: 10.5px;

}


/* =========================================================
   EMPTY ADD BUTTON
========================================================= */

.empty-add-btn {

    height: 33px;

    padding: 0 12px;

    border: 1px solid #d8e7fa;

    border-radius: 8px;

    background: #edf5ff;

    color: #147cf5;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    font-size: 10.5px;

    font-weight: 650;

    cursor: pointer;

    transition:
        background .2s ease,
        color .2s ease,
        transform .2s ease,
        box-shadow .2s ease;

}


.empty-add-btn:hover {

    background: #147cf5;

    color: #fff;

    transform: translateY(-1px);

    box-shadow:
        0 5px 12px rgba(20,124,245,.15);

}


/* =========================================================
   ACTIVITY FOOTER
========================================================= */

.activity-footer {

    min-height: 58px;

    padding: 10px 19px;

    border-top: 1px solid #edf0f5;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

}


.activity-showing {

    color: #94a3b8;

    font-size: 10.5px;

}


.activity-showing strong {

    color: #64748b;

    font-weight: 700;

}


/* =========================================================
   PAGINATION
========================================================= */

.activity-pagination {

    display: flex;

    align-items: center;

    gap: 5px;

}


.pagination-btn,
.pagination-number {

    width: 30px;

    height: 30px;

    border-radius: 7px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 10px;

    font-weight: 650;

}


.pagination-btn {

    border: 1px solid #e5eaf1;

    background: #fff;

    color: #64748b;

    cursor: pointer;

}


.pagination-number {

    border: 1px solid #147cf5;

    background: #147cf5;

    color: #fff;

}


.pagination-btn:hover:not(.disabled) {

    background: #edf5ff;

    border-color: #d5e4f7;

    color: #147cf5;

}


.pagination-btn.disabled {

    background: #f8fafc;

    color: #cbd5e1;

    cursor: not-allowed;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (min-width: 992px) and (max-width: 1199px) {

    .sports-stats-row {

        gap: 10px;

    }


    .sports-stat-card {

        padding: 17px 15px;

        gap: 10px;

    }


    .sports-stat-icon {

        width: 43px;

        height: 43px;

        min-width: 43px;

        font-size: 16px;

    }


    .sports-stat-info > span {

        font-size: 10px;

    }


    .sports-stat-info h3 {

        font-size: 23px;

    }


    .sports-stat-info small {

        font-size: 8.5px;

    }

}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 991px) {

    .sports-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .sports-header-actions {

        width: 100%;

    }


    .sports-primary-btn,
    .sports-outline-btn {

        flex: 1;

    }


    .sports-stats-row {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {

    .sports-page .container-fluid {

        padding-top: 18px !important;

    }


    .sports-header-left {

        align-items: flex-start;

    }


    .sports-header h2 {

        font-size: 21px;

    }


    .panel-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .panel-header-actions {

        width: 100%;

    }


    .activity-filter-btn {

        flex: 1;

    }


    .activity-refresh-btn {

        min-width: 34px;

    }


    .activity-footer {

        align-items: flex-start;

        flex-direction: column;

    }


    .activity-pagination {

        width: 100%;

        justify-content: flex-end;

    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .sports-header-actions {

        flex-direction: column;

    }


    .sports-primary-btn,
    .sports-outline-btn {

        width: 100%;

        flex: none;

    }


    .sports-stats-row {

        grid-template-columns: 1fr;

        gap: 12px;

    }


    .sports-stat-card {

        min-height: 120px;

        padding: 17px 18px;

    }


    .sports-stat-icon {

        width: 45px;

        height: 45px;

        min-width: 45px;

    }


    .sports-stat-info h3 {

        font-size: 23px;

    }


    .panel-heading {

        align-items: flex-start;

    }


    .panel-header {

        padding: 14px 15px;

    }


    .activity-footer {

        padding: 10px 15px;

    }

}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .sports-header-left {

        gap: 10px;

    }


    .sports-title-icon {

        width: 42px;

        height: 42px;

        min-width: 42px;

        font-size: 17px;

    }


    .sports-header h2 {

        font-size: 19px;

    }


    .sports-header p {

        font-size: 11px;

    }


    .sports-stat-card {

        gap: 11px;

    }

}

</style>

@endsection