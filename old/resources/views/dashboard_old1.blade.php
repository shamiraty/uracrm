
@extends('layouts.app') <!-- Assuming you have a base layout -->

@section('content')
<div class="dashboard-main-body">

  <!-- Header with Breadcrumbs -->
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Dashboard</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="{{ url('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          Dashboard
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">CRM</li>
    </ul>
  </div>


  <div class="row gy-4">
    <div class="col-xxl-8">
      <div class="row gy-4">

        <div class="col-xxl-4 col-sm-6">
          <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-1">
            <div class="card-body p-0">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                  <div class="d-flex align-items-center gap-2">
                    <span class="mb-0 w-48-px h-48-px bg-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                      <iconify-icon icon="mingcute:user-follow-fill" class="icon"></iconify-icon>
                    </span>
                    <div>
                      <span class="mb-2 fw-medium text-secondary-light text-sm">New Users</span>
                      <h6 class="fw-semibold">15,000</h6>
                    </div>
                  </div>

                  <div id="new-user-chart" class="remove-tooltip-title rounded-tooltip-value"></div>
              </div>
              <p class="text-sm mb-0">Increase by  <span class="bg-success-focus px-1 rounded-2 fw-medium text-success-main text-sm">+200</span> this week</p>
            </div>
          </div>
        </div>

        <div class="col-xxl-4 col-sm-6">
          <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-2">
            <div class="card-body p-0">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                  <div class="d-flex align-items-center gap-2">
                    <span class="mb-0 w-48-px h-48-px bg-success-main flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6">
                      <iconify-icon icon="mingcute:user-follow-fill" class="icon"></iconify-icon>
                    </span>
                    <div>
                      <span class="mb-2 fw-medium text-secondary-light text-sm">Active Users</span>
                      <h6 class="fw-semibold">8,000</h6>
                    </div>
                  </div>

                  <div id="active-user-chart" class="remove-tooltip-title rounded-tooltip-value"></div>
              </div>
              <p class="text-sm mb-0">Increase by  <span class="bg-success-focus px-1 rounded-2 fw-medium text-success-main text-sm">+200</span> this week</p>
            </div>
          </div>
        </div>

        <div class="col-xxl-4 col-sm-6">
          <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-3">
            <div class="card-body p-0">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                  <div class="d-flex align-items-center gap-2">
                    <span class="mb-0 w-48-px h-48-px bg-yellow text-white flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                      <iconify-icon icon="iconamoon:discount-fill" class="icon"></iconify-icon>
                    </span>
                    <div>
                      <span class="mb-2 fw-medium text-secondary-light text-sm">Total Sales</span>
                      <h6 class="fw-semibold">$5,00,000</h6>
                    </div>
                  </div>

                  <div id="total-sales-chart" class="remove-tooltip-title rounded-tooltip-value"></div>
              </div>
              <p class="text-sm mb-0">Increase by  <span class="bg-danger-focus px-1 rounded-2 fw-medium text-danger-main text-sm">-$10k</span> this week</p>
            </div>
          </div>
        </div>

        <div class="col-xxl-4 col-sm-6">
          <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-4">
            <div class="card-body p-0">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                  <div class="d-flex align-items-center gap-2">
                    <span class="mb-0 w-48-px h-48-px bg-purple text-white flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                      <iconify-icon icon="mdi:message-text" class="icon"></iconify-icon>
                    </span>
                    <div>
                      <span class="mb-2 fw-medium text-secondary-light text-sm">Conversion</span>
                      <h6 class="fw-semibold">25%</h6>
                    </div>
                  </div>

                  <div id="conversion-user-chart" class="remove-tooltip-title rounded-tooltip-value"></div>
              </div>
              <p class="text-sm mb-0">Increase by  <span class="bg-success-focus px-1 rounded-2 fw-medium text-success-main text-sm">+5%</span> this week</p>
            </div>
          </div>
        </div>

        <div class="col-xxl-4 col-sm-6">
          <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-5">
            <div class="card-body p-0">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                  <div class="d-flex align-items-center gap-2">
                    <span class="mb-0 w-48-px h-48-px bg-pink text-white flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                      <iconify-icon icon="mdi:leads" class="icon"></iconify-icon>
                    </span>
                    <div>
                      <span class="mb-2 fw-medium text-secondary-light text-sm">Leads</span>
                      <h6 class="fw-semibold">250</h6>
                    </div>
                  </div>

                  <div id="leads-chart" class="remove-tooltip-title rounded-tooltip-value"></div>
              </div>
              <p class="text-sm mb-0">Increase by  <span class="bg-success-focus px-1 rounded-2 fw-medium text-success-main text-sm">+20</span> this week</p>
            </div>
          </div>
        </div>

        <div class="col-xxl-4 col-sm-6">
          <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-6">
            <div class="card-body p-0">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                  <div class="d-flex align-items-center gap-2">
                    <span class="mb-0 w-48-px h-48-px bg-cyan text-white flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                      <iconify-icon icon="streamline:bag-dollar-solid" class="icon"></iconify-icon>
                    </span>
                    <div>
                      <span class="mb-2 fw-medium text-secondary-light text-sm">Total Profit</span>
                      <h6 class="fw-semibold">$3,00,700</h6>
                    </div>
                  </div>

                  <div id="total-profit-chart" class="remove-tooltip-title rounded-tooltip-value"></div>
              </div>
              <p class="text-sm mb-0">Increase by  <span class="bg-success-focus px-1 rounded-2 fw-medium text-success-main text-sm">+$15k</span> this week</p>
            </div>
          </div>
        </div>

      </div>
    </div>
    <!-- Revenue Growth start -->
    <div class="col-xxl-4">
      <div class="card h-100 radius-8 border">
        <div class="card-body p-24">
          <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
            <div>
              <h6 class="mb-2 fw-bold text-lg">Revenue Growth</h6>
              <span class="text-sm fw-medium text-secondary-light">Weekly Report</span>
            </div>
            <div class="text-end">
              <h6 class="mb-2 fw-bold text-lg">$50,000.00</h6>
              <span class="bg-success-focus ps-12 pe-12 pt-2 pb-2 rounded-2 fw-medium text-success-main text-sm">$10k</span>
            </div>
          </div>
          <div id="revenue-chart" class="mt-28"></div>
        </div>
      </div>
    </div>
    <!-- Revenue Growth End -->

    <!-- Earning Static start -->
    <div class="col-xxl-8">
      <div class="card h-100 radius-8 border-0">
        <div class="card-body p-24">
          <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
            <div>
              <h6 class="mb-2 fw-bold text-lg">Earning Statistic</h6>
              <span class="text-sm fw-medium text-secondary-light">Yearly earning overview</span>
            </div>
            <div class="">
              <select class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                <option>Yearly</option>
                <option>Monthly</option>
                <option>Weekly</option>
                <option>Today</option>
              </select>
            </div>
          </div>

          <div class="mt-20 d-flex justify-content-center flex-wrap gap-3">

            <div class="d-inline-flex align-items-center gap-2 p-2 radius-8 border pe-36 br-hover-primary group-item">
              <span class="bg-neutral-100 w-44-px h-44-px text-xxl radius-8 d-flex justify-content-center align-items-center text-secondary-light group-hover:bg-primary-600 group-hover:text-white">
                <iconify-icon icon="fluent:cart-16-filled" class="icon"></iconify-icon>
              </span>
              <div>
                <span class="text-secondary-light text-sm fw-medium">Sales</span>
                <h6 class="text-md fw-semibold mb-0">$200k</h6>
              </div>
            </div>

            <div class="d-inline-flex align-items-center gap-2 p-2 radius-8 border pe-36 br-hover-primary group-item">
              <span class="bg-neutral-100 w-44-px h-44-px text-xxl radius-8 d-flex justify-content-center align-items-center text-secondary-light group-hover:bg-primary-600 group-hover:text-white">
                <iconify-icon icon="uis:chart" class="icon"></iconify-icon>
              </span>
              <div>
                <span class="text-secondary-light text-sm fw-medium">Income</span>
                <h6 class="text-md fw-semibold mb-0">$200k</h6>
              </div>
            </div>

            <div class="d-inline-flex align-items-center gap-2 p-2 radius-8 border pe-36 br-hover-primary group-item">
              <span class="bg-neutral-100 w-44-px h-44-px text-xxl radius-8 d-flex justify-content-center align-items-center text-secondary-light group-hover:bg-primary-600 group-hover:text-white">
                <iconify-icon icon="ph:arrow-fat-up-fill" class="icon"></iconify-icon>
              </span>
              <div>
                <span class="text-secondary-light text-sm fw-medium">Profit</span>
                <h6 class="text-md fw-semibold mb-0">$200k</h6>
              </div>
            </div>
          </div>

          <div id="barChart" class="barChart"></div>
        </div>
      </div>
    </div>
    <!-- Earning Static End -->

    <!-- Campaign Static start -->
    <div class="col-xxl-4">
      <div class="row gy-4">
        <div class="col-xxl-12 col-sm-6">
          <div class="card h-100 radius-8 border-0">
            <div class="card-body p-24">
              <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                <h6 class="mb-2 fw-bold text-lg">Campaigns</h6>
                <div class="">
                  <select class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                    <option>Yearly</option>
                    <option>Monthly</option>
                    <option>Weekly</option>
                    <option>Today</option>
                  </select>
                </div>
              </div>

              <div class="mt-3">

                <div class="d-flex align-items-center justify-content-between gap-3 mb-12">
                  <div class="d-flex align-items-center">
                    <span class="text-xxl line-height-1 d-flex align-content-center flex-shrink-0 text-orange">
                      <iconify-icon icon="majesticons:mail" class="icon"></iconify-icon>
                    </span>
                    <span class="text-primary-light fw-medium text-sm ps-12">Email</span>
                  </div>
                  <div class="d-flex align-items-center gap-2 w-100">
                    <div class="w-100 max-w-66 ms-auto">
                      <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-orange rounded-pill" style="width: 80%;"></div>
                      </div>
                    </div>
                    <span class="text-secondary-light font-xs fw-semibold">80%</span>
                  </div>
                </div>

                <div class="d-flex align-items-center justify-content-between gap-3 mb-12">
                  <div class="d-flex align-items-center">
                    <span class="text-xxl line-height-1 d-flex align-content-center flex-shrink-0 text-success-main">
                      <iconify-icon icon="eva:globe-2-fill" class="icon"></iconify-icon>
                    </span>
                    <span class="text-primary-light fw-medium text-sm ps-12">Website</span>
                  </div>
                  <div class="d-flex align-items-center gap-2 w-100">
                    <div class="w-100 max-w-66 ms-auto">
                      <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-success-main rounded-pill" style="width: 60%;"></div>
                      </div>
                    </div>
                    <span class="text-secondary-light font-xs fw-semibold">60%</span>
                  </div>
                </div>

                <div class="d-flex align-items-center justify-content-between gap-3 mb-12">
                  <div class="d-flex align-items-center">
                    <span class="text-xxl line-height-1 d-flex align-content-center flex-shrink-0 text-info-main">
                      <iconify-icon icon="fa6-brands:square-facebook" class="icon"></iconify-icon>
                    </span>
                    <span class="text-primary-light fw-medium text-sm ps-12">Facebook</span>
                  </div>
                  <div class="d-flex align-items-center gap-2 w-100">
                    <div class="w-100 max-w-66 ms-auto">
                      <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-info-main rounded-pill" style="width: 49%;"></div>
                      </div>
                    </div>
                    <span class="text-secondary-light font-xs fw-semibold">49%</span>
                  </div>
                </div>

                <div class="d-flex align-items-center justify-content-between gap-3">
                  <div class="d-flex align-items-center">
                    <span class="text-xxl line-height-1 d-flex align-content-center flex-shrink-0 text-indigo">
                      <iconify-icon icon="fluent:location-off-20-filled" class="icon"></iconify-icon>
                    </span>
                    <span class="text-primary-light fw-medium text-sm ps-12">Email</span>
                  </div>
                  <div class="d-flex align-items-center gap-2 w-100">
                    <div class="w-100 max-w-66 ms-auto">
                      <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-indigo rounded-pill" style="width: 70%;"></div>
                      </div>
                    </div>
                    <span class="text-secondary-light font-xs fw-semibold">70%</span>
                  </div>
                </div>

              </div>

            </div>
          </div>
        </div>
        <div class="col-xxl-12 col-sm-6">
          <div class="card h-100 radius-8 border-0 overflow-hidden">
            <div class="card-body p-24">
              <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                <h6 class="mb-2 fw-bold text-lg">Customer Overview</h6>
                <div class="">
                  <select class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                    <option>Yearly</option>
                    <option>Monthly</option>
                    <option>Weekly</option>
                    <option>Today</option>
                  </select>
                </div>
              </div>

              <div class="d-flex flex-wrap align-items-center mt-3">
                <ul class="flex-shrink-0">
                  <li class="d-flex align-items-center gap-2 mb-28">
                    <span class="w-12-px h-12-px rounded-circle bg-success-main"></span>
                    <span class="text-secondary-light text-sm fw-medium">Total: 500</span>
                  </li>
                  <li class="d-flex align-items-center gap-2 mb-28">
                    <span class="w-12-px h-12-px rounded-circle bg-warning-main"></span>
                    <span class="text-secondary-light text-sm fw-medium">New: 500</span>
                  </li>
                  <li class="d-flex align-items-center gap-2">
                    <span class="w-12-px h-12-px rounded-circle bg-primary-600"></span>
                    <span class="text-secondary-light text-sm fw-medium">Active: 1500</span>
                  </li>
                </ul>
                <div id="donutChart" class="flex-grow-1 apexcharts-tooltip-z-none title-style circle-none"></div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Campaign Static End -->

    <!-- Client Payment Status Start -->
    <div class="col-xxl-4 col-sm-6">
      <div class="card h-100 radius-8 border-0">
        <div class="card-body p-24">
            <h6 class="mb-2 fw-bold text-lg">Client Payment Status</h6>
            <span class="text-sm fw-medium text-secondary-light">Weekly Report</span>

            <ul class="d-flex flex-wrap align-items-center justify-content-center mt-32">
              <li class="d-flex align-items-center gap-2 me-28">
                <span class="w-12-px h-12-px rounded-circle bg-success-main"></span>
                <span class="text-secondary-light text-sm fw-medium">Paid: 500</span>
              </li>
              <li class="d-flex align-items-center gap-2 me-28">
                <span class="w-12-px h-12-px rounded-circle bg-info-main"></span>
                <span class="text-secondary-light text-sm fw-medium">Pending: 500</span>
              </li>
              <li class="d-flex align-items-center gap-2">
                <span class="w-12-px h-12-px rounded-circle bg-warning-main"></span>
                <span class="text-secondary-light text-sm fw-medium">Overdue: 1500</span>
              </li>
            </ul>
            <div class="mt-40">
              <div id="paymentStatusChart" class="margin-16-minus"></div>
            </div>
        </div>
      </div>
    </div>
    <!-- Client Payment Status End -->

    <!-- Country Status Start -->
    <div class="col-xxl-4 col-sm-6">
      <div class="card radius-8 border-0">

        <div class="card-body">
          <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
            <h6 class="mb-2 fw-bold text-lg">Countries Status</h6>
            <div class="">
              <select class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                <option>Yearly</option>
                <option>Monthly</option>
                <option>Weekly</option>
                <option>Today</option>
              </select>
            </div>
          </div>
        </div>

        <div id="world-map"></div>

        <div class="card-body p-24 max-h-266-px scroll-sm overflow-y-auto">
          <div class="">

            <div class="d-flex align-items-center justify-content-between gap-3 mb-3 pb-2">
              <div class="d-flex align-items-center w-100">
                <img src="assets/images/flags/flag1.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                <div class="flex-grow-1">
                  <h6 class="text-sm mb-0">USA</h6>
                  <span class="text-xs text-secondary-light fw-medium">1,240 Users</span>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2 w-100">
                <div class="w-100 max-w-66 ms-auto">
                  <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar bg-primary-600 rounded-pill" style="width: 80%;"></div>
                  </div>
                </div>
                <span class="text-secondary-light font-xs fw-semibold">80%</span>
              </div>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-3 mb-3 pb-2">
              <div class="d-flex align-items-center w-100">
                <img src="assets/images/flags/flag2.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                <div class="flex-grow-1">
                  <h6 class="text-sm mb-0">Japan</h6>
                  <span class="text-xs text-secondary-light fw-medium">1,240 Users</span>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2 w-100">
                <div class="w-100 max-w-66 ms-auto">
                  <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar bg-orange rounded-pill" style="width: 60%;"></div>
                  </div>
                </div>
                <span class="text-secondary-light font-xs fw-semibold">60%</span>
              </div>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-3 mb-3 pb-2">
              <div class="d-flex align-items-center w-100">
                <img src="assets/images/flags/flag3.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                <div class="flex-grow-1">
                  <h6 class="text-sm mb-0">France</h6>
                  <span class="text-xs text-secondary-light fw-medium">1,240 Users</span>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2 w-100">
                <div class="w-100 max-w-66 ms-auto">
                  <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar bg-yellow rounded-pill" style="width: 49%;"></div>
                  </div>
                </div>
                <span class="text-secondary-light font-xs fw-semibold">49%</span>
              </div>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-3">
              <div class="d-flex align-items-center w-100">
                <img src="assets/images/flags/flag4.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                <div class="flex-grow-1">
                  <h6 class="text-sm mb-0">Germany</h6>
                  <span class="text-xs text-secondary-light fw-medium">1,240 Users</span>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2 w-100">
                <div class="w-100 max-w-66 ms-auto">
                  <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar bg-success-main rounded-pill" style="width: 100%;"></div>
                  </div>
                </div>
                <span class="text-secondary-light font-xs fw-semibold">100%</span>
              </div>
            </div>

          </div>

        </div>
      </div>
    </div>
    <!-- Country Status End -->

    <!-- Top performance Start -->
    <div class="col-xxl-4">
      <div class="card">

        <div class="card-body">
          <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
            <h6 class="mb-2 fw-bold text-lg mb-0">Top Performer</h6>
            <a href="javascript:void(0)" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
              View All
              <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
            </a>
          </div>

          <div class="mt-32">

            <div class="d-flex align-items-center justify-content-between gap-3 mb-32">
              <div class="d-flex align-items-center">
                <img src="assets/images/users/user1.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                <div class="flex-grow-1">
                  <h6 class="text-md mb-0">Dianne Russell</h6>
                  <span class="text-sm text-secondary-light fw-medium">Agent ID: 36254</span>
                </div>
              </div>
              <span class="text-primary-light text-md fw-medium">60/80</span>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-3 mb-32">
              <div class="d-flex align-items-center">
                <img src="assets/images/users/user2.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                <div class="flex-grow-1">
                  <h6 class="text-md mb-0">Wade Warren</h6>
                  <span class="text-sm text-secondary-light fw-medium">Agent ID: 36254</span>
                </div>
              </div>
              <span class="text-primary-light text-md fw-medium">50/70</span>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-3 mb-32">
              <div class="d-flex align-items-center">
                <img src="assets/images/users/user3.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                <div class="flex-grow-1">
                  <h6 class="text-md mb-0">Albert Flores</h6>
                  <span class="text-sm text-secondary-light fw-medium">Agent ID: 36254</span>
                </div>
              </div>
              <span class="text-primary-light text-md fw-medium">55/75</span>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-3 mb-32">
              <div class="d-flex align-items-center">
                <img src="assets/images/users/user4.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                <div class="flex-grow-1">
                  <h6 class="text-md mb-0">Bessie Cooper</h6>
                  <span class="text-sm text-secondary-light fw-medium">Agent ID: 36254</span>
                </div>
              </div>
              <span class="text-primary-light text-md fw-medium">60/80</span>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-3 mb-32">
              <div class="d-flex align-items-center">
                <img src="assets/images/users/user5.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                <div class="flex-grow-1">
                  <h6 class="text-md mb-0">Arlene McCoy</h6>
                  <span class="text-sm text-secondary-light fw-medium">Agent ID: 36254</span>
                </div>
              </div>
              <span class="text-primary-light text-md fw-medium">55/75</span>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-3">
              <div class="d-flex align-items-center">
                <img src="assets/images/users/user1.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                <div class="flex-grow-1">
                  <h6 class="text-md mb-0">Arlene McCoy</h6>
                  <span class="text-sm text-secondary-light fw-medium">Agent ID: 36254</span>
                </div>
              </div>
              <span class="text-primary-light text-md fw-medium">50/70</span>
            </div>

          </div>

        </div>
      </div>
    </div>
    <!-- Top performance End -->

    <!-- Latest Performance Start -->
    <div class="col-xxl-12">
  <div class="card h-100">
    <div class="card-header border-bottom bg-base ps-0 py-0 pe-24 d-flex align-items-center justify-content-between">
      <ul class="nav bordered-tab nav-pills mb-0" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="pills-to-do-list-tab" data-bs-toggle="pill" data-bs-target="#pills-to-do-list" type="button" role="tab" aria-controls="pills-to-do-list" aria-selected="true">All Item</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-recent-leads-tab" data-bs-toggle="pill" data-bs-target="#pills-recent-leads" type="button" role="tab" aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">Best Match</button>
        </li>
      </ul>
      <a href="javascript:void(0)" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
        View All
        <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
      </a>
    </div>
    <div class="card-body p-24">
      <div class="tab-content" id="pills-tabContent">
        <!-- First Tab Content: Payment Amounts Grouped by Enquiry Type -->
        <div class="tab-pane fade show active" id="pills-to-do-list" role="tabpanel" aria-labelledby="pills-to-do-list-tab" tabindex="0">
          <h1>Payment Amounts Grouped by Enquiry Type</h1>
          <div class="table-responsive scroll-sm">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Enquiry Type</th>
                  <th>Total Payment Amount</th>
                </tr>
              </thead>
              <tbody>
                @foreach($totalPaymentsByType as $entry)
                  <tr>
                    <td>{{ $entry->type }}</td>
                    <td>{{ $entry->total_amount }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <!-- Second Tab Content: Total Payments Grouped by Enquiry Type -->
        <div class="tab-pane fade" id="pills-recent-leads" role="tabpanel" aria-labelledby="pills-recent-leads-tab" tabindex="0">
          <h1>Total Payments Grouped by Enquiry Type</h1>
          <div class="container">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Enquiry Type</th>
                  <th>Payment Status</th>
                  <th>Payment Count</th>
                  <th>Total Payment Amount</th>
                </tr>
              </thead>
              <tbody>
                @php
                    $previousType = '';
                @endphp

                @foreach($totalPaymentsByStatus as $entry)
                  @if($entry->type !== $previousType)
                    <!-- Show Enquiry Type row -->
                    @if($previousType !== '')
                      <!-- Subtotal for previous type, shown after all statuses -->
                      <tr>
                          <td colspan="2"><strong>{{ str_replace('_', ' ', $previousType) }} Subtotal</strong></td>
                          <td><strong>{{ $typeSubtotals[$previousType]['payment_count'] }}</strong></td>
                          <td><strong>{{ number_format($typeSubtotals[$previousType]['total_amount'], 2) }}</strong></td>
                      </tr>
                    @endif
                    <tr>
                      <td rowspan="{{ count($totalPaymentsByStatus->where('type', $entry->type)) }}" style="vertical-align: middle;">
                        {{ str_replace('_', ' ', $entry->type) }}
                      </td>
                      <td>{{ $entry->status }}</td>
                      <td>{{ $entry->payment_count }}</td>
                      <td>{{ number_format($entry->total_amount, 2) }}</td>
                    </tr>
                    @php
                        $previousType = $entry->type;
                    @endphp
                  @else
                    <!-- Continue displaying status rows for same type -->
                    <tr>
                      <td>{{ $entry->status }}</td>
                      <td>{{ $entry->payment_count }}</td>
                      <td>{{ number_format($entry->total_amount, 2) }}</td>
                    </tr>
                  @endif
                @endforeach

                <!-- Subtotal for the last type -->
                @if($previousType !== '')
                  <tr>
                    <td colspan="2"><strong>{{ str_replace('_', ' ', $previousType) }} Subtotal</strong></td>
                    <td><strong>{{ $typeSubtotals[$previousType]['payment_count'] }}</strong></td>
                    <td><strong>{{ number_format($typeSubtotals[$previousType]['total_amount'], 2) }}</strong></td>
                  </tr>
                @endif
              </tbody>

              <tfoot>
                <!-- Grand Total row -->
                <tr>
                  <td colspan="2"><strong>Grand Total</strong></td>
                  <td><strong>{{ $grandTotalCount }}</strong></td>
                  <td><strong>{{ number_format($grandTotalAmount, 2) }}</strong></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


 
    <!-- Latest Performance End -->
  </div>








  <div class="container">
        <h1>Payment Amounts Grouped by Enquiry Type</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Enquiry Type</th>
                    <th>Total Payment Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($totalPaymentsByType as $entry)
                    <tr>
                        <td>{{ $entry->type }}</td>
                        <td>{{ $entry->total_amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="container">
    <h1>Total Payments Grouped by Enquiry Type</h1>
    <div class="container">
    <h1>Total Payments Grouped by Enquiry Type</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Enquiry Type</th>
                <th>Payment Status</th>
                <th>Payment Count</th>
                <th>Total Payment Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $previousType = '';
            @endphp

            @foreach($totalPaymentsByStatus as $entry)
                @if($entry->type !== $previousType)
                    <!-- Show Enquiry Type row -->
                    @if($previousType !== '')
                        <!-- Subtotal for previous type, shown after all statuses -->
                        <tr>
                            <td colspan="2"><strong>{{ str_replace('_', ' ', $previousType) }} Subtotal</strong></td>
                            <td><strong>{{ $typeSubtotals[$previousType]['payment_count'] }}</strong></td>
                            <td><strong>{{ number_format($typeSubtotals[$previousType]['total_amount'], 2) }}</strong></td>
                        </tr>
                    @endif
                    <tr>
                        <td rowspan="{{ count($totalPaymentsByStatus->where('type', $entry->type)) }}" style="vertical-align: middle;">
                            {{ str_replace('_', ' ', $entry->type) }}
                        </td>
                        <td>{{ $entry->status }}</td>
                        <td>{{ $entry->payment_count }}</td>
                        <td>{{ number_format($entry->total_amount, 2) }}</td>
                    </tr>
                    @php
                        $previousType = $entry->type;
                    @endphp
                @else
                    <!-- Continue displaying status rows for same type -->
                    <tr>
                        <td>{{ $entry->status }}</td>
                        <td>{{ $entry->payment_count }}</td>
                        <td>{{ number_format($entry->total_amount, 2) }}</td>
                    </tr>
                @endif
            @endforeach

            <!-- Subtotal for the last type -->
            @if($previousType !== '')
                <tr>
                    <td colspan="2"><strong>{{ str_replace('_', ' ', $previousType) }} Subtotal</strong></td>
                    <td><strong>{{ $typeSubtotals[$previousType]['payment_count'] }}</strong></td>
                    <td><strong>{{ number_format($typeSubtotals[$previousType]['total_amount'], 2) }}</strong></td>
                </tr>
            @endif
        </tbody>

        <tfoot>
            <!-- Grand Total row -->
            <tr>
                <td colspan="2"><strong>Grand Total</strong></td>
                <td><strong>{{ $grandTotalCount }}</strong></td>
                <td><strong>{{ number_format($grandTotalAmount, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>

 


</div>

@endsection

