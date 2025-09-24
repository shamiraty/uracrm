@extends('layouts.app') <!-- Assuming you have a base layout -->
@section('content')
<!--
<div class="dashboard-main-body">
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
-->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  /*... your existing styles... */

  /* Additional styles for the list layout */
.progress {
    height: 0.5rem;
  }
.progress-sm {
    height: 0.375rem;
  }
.w-12-px {
    width: 12px;
  }
.h-12-px {
    height: 12px;
  }
</style>
<div class="container-fluid my-1">


<div class="row">
<div class="col-md-6">
<div class="card shadow-sm mt-1">
        <div class="card-header">
          <p class="fw-bold text-lg">Loan Category</p>
        </div>
        <div class="card-body">
          <canvas id="loanCategoryChart"></canvas>
        </div>
      </div>
</div>

      <div class="col-md-6">
      <div class="card shadow-sm mt-1">
        <div class="card-header">
          <p class="fw-bold text-lg">Loan Applications by Month ({{ now()->year }})</p>
        </div>
        <div class="card-body">
          <canvas id="monthlyLoanChart"></canvas>
        </div>
      </div>
</div>
</div>






<div class="row mt-4">
<div class="col-md-12">   
<div class="card radius-12 shadow-sm">
<div class="card-body p-16">
 <!-- Cards Section (Loan Applications, Memberships, Membership Changes, Refunds) -->
<div class="row gy-1">
<div class="col-md-4"> 
<div class="card  shadow-lg h-100">
                <div class="card-header">
                    <h6 class="mb-2 fw-bold text-lg mb-0">Applications</h6>
                </div>
                <div class="card-body">
<div class="col-xxl-12 col-sm-12">
    <div class="px-20 py-16 shadow-sm radius-8 h-100 gradient-deep-1 left-line line-bg-primary position-relative overflow-hidden">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
            <div>
                <span class="mb-2 fw-medium text-secondary-light text-md">Loan Applications</span>
                <h6 class="fw-semibold mb-1">{{ $loanApplicationCount }}</h6>
            </div>
            <span class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-primary-100 text-primary-600">
                <i class="ri-hand-holding-usd-fill"></i>
            </span>
        </div>
        <p class="text-sm mb-0"></p>
    </div>
</div>

<!-- Memberships Card -->
<div class="col-xxl-12 col-sm-12 mt-1">
    <div class="px-20 py-16 shadow-sm radius-8 h-100 gradient-deep-2 left-line line-bg-info position-relative overflow-hidden">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
            <div>
                <span class="mb-2 fw-medium text-secondary-light text-md">Memberships</span>
                <h6 class="fw-semibold mb-1">{{ $membershipCount }}</h6>
            </div>
            <span class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-info-200 text-info-600">
                <i class="ri-account-box-fill"></i>
            </span>
        </div>
        <p class="text-sm mb-0"></p>
    </div>
</div>

<!-- Membership Changes Card -->
<div class="col-xxl-12 col-sm-12 mt-1">
    <div class="px-20 py-16 shadow-sm radius-8 h-100 gradient-deep-3 left-line line-bg-warning position-relative overflow-hidden">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
            <div>
                <span class="mb-2 fw-medium text-secondary-light text-md">Membership Changes</span>
                <h6 class="fw-semibold mb-1">{{ $membershipChangeCount }}</h6>
            </div>
            <span class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-warning-100 text-warning-600">
                <i class="ri-refresh-line"></i>
            </span>
        </div>
        <p class="text-sm mb-0"></p>
    </div>
</div>

<!-- Refunds Card -->
<div class="col-xxl-12 col-sm-12 mt-1">
    <div class="px-20 py-16 shadow-sm radius-8 h-100 gradient-deep-4 left-line line-bg-danger position-relative overflow-hidden">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
            <div>
                <span class="mb-2 fw-medium text-secondary-light text-md">Refunds</span>
                <h6 class="fw-semibold mb-1">{{ $refundCount }}</h6>
            </div>
            <span class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-danger-100 text-danger-600">
                <i class="ri-money-dollar-circle-line"></i>
            </span>
        </div>
        <p class="text-sm mb-0"></p>
    </div>
</div>
</div>
</div>
</div>
<div class="col-md-5"> 
<!-- Deduction Adjustment Section -->
 
    <div class="card h-100 radius-8 border-0 shadow-lg">
        <div class="card-body p-24">
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                <h6 class="mb-2 fw-bold text-lg">Saving Adjustment</h6>
                <div>
                    <select class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                        <option>Yearly</option>
                        <option>Monthly</option>
                        <option>Weekly</option>
                        <option>Today</option>
                    </select>
                </div>
            </div>

            <div class="position-relative">
                <div id="statisticsDonutChart" class="mt-36 flex-grow-1 apexcharts-tooltip-z-none title-style circle-none"></div>
                <span class="w-80-px h-80-px bg-info shadow text-primary-light fw-semibold text-xl d-flex justify-content-center align-items-center rounded-circle position-absolute end-0 top-0 z-1">
                    -{{ number_format($fromPercentage, 1) }}%
                </span>
                <span class="w-80-px h-80-px bg-warning shadow text-primary-light fw-semibold text-xl d-flex justify-content-center align-items-center rounded-circle position-absolute start-0 bottom-0 z-1">
                    +{{ number_format($toPercentage, 1) }}%
                </span>
            </div>

            <ul class="d-flex flex-wrap align-items-center justify-content-between mt-3 gap-3">
                <li class="d-flex align-items-center gap-2">
                    <span class="w-12-px h-12-px radius-2 bg-primary-600"></span>
                    <span class="text-secondary-light text-sm fw-normal"> (Reduce): 
                        <span class="text-primary-light fw-bold">Tsh: {{ number_format($fromAmount) }} ({{ number_format($fromPercentage,1) }}%)</span>
                    </span>
                </li>
                <li class="d-flex align-items-center gap-2">
                    <span class="w-12-px h-12-px radius-2 bg-yellow"></span>
                    <span class="text-secondary-light text-sm fw-normal">(Increase): 
                        <span class="text-primary-light fw-bold">Tsh: {{ number_format($toAmount) }} ({{ number_format($toPercentage,1) }}%)</span>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>
    <div class="col-md-3"> 
    <div class="card  shadow-lg h-100">
                <div class="card-header">
                    <h6 class="mb-2 fw-bold text-lg mb-0">Overview Deductions</h6>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Category</th>
                                    <th scope="col">Count / Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <!--<td>Contributions</td>-->
                                    <!--<td>{{ $contributionCount }}</td>-->
                                </tr>
                                <tr>
                                    <td>URA Members</td>
                                    <td>{{ $uraMembersCount }}</td>
                                </tr>
                                <tr>
                                    <td>Salary Loans (769)</td>
                                    <td>{{ $salaryLoansCount }}</td>
                                </tr>
                                <tr>
                                    <td>Salary Loans (769A)</td>
                                    <td>{{ $salaryLoans769ACount }}</td>
                                </tr>
                                <tr>
                                    <td>Total Salary Loans (769)</td>
                                    <td>TSh {{ number_format($salaryLoansSum, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Salary Loans (769A)</td>
                                    <td>TSh {{ number_format($salaryLoans769ASum, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
</div>
</div>
</div>
 

                
    <div class="col-xxl-12 mt-4">
    <div class="card h-100">
        <div class="card-header">
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                <h6 class="mb-2 fw-bold text-lg mb-0"></h6>
                <a href="javascript:void(0)" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                    View All
                    <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                </a>
            </div>
        </div>
        <div class="card-body p-24">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table mb-0"id="dataTable">
                    <thead>
                        <tr>
                            <th scope="col">Department</th>
                            <th scope="col">Contribution Count</th>
                            <th scope="col">Salary Loans (769) Count</th>
                            <th scope="col">Salary Loans (769A) Count</th>
                            <th scope="col">Salary Loans (769) Total</th>
                            <th scope="col">Salary Loans (769A) Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deductionsByDept as $dept)
                            <tr>
                                <td>{{ $dept->deptName }}</td>
                                <td>{{ $dept->contributionCount }}</td>
                                <td>{{ $dept->salaryLoans769Count }}</td>
                                <td>{{ $dept->salaryLoans769ACount }}</td>
                                <td>TSh {{ number_format($dept->salaryLoans769Sum, 2) }}</td>
                                <td>TSh {{ number_format($dept->salaryLoans769ASum, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-success font-weight-bold">
                            <td>Grand Total</td>
                            <td>{{ $grandTotalContributions }}</td>
                            <td>{{ $grandTotalSalaryLoans769Count }}</td>
                            <td>{{ $grandTotalSalaryLoans769ACount }}</td>
                            <td>TSh {{ number_format($grandTotalSalaryLoans769Sum, 2) }}</td>
                            <td>TSh {{ number_format($grandTotalSalaryLoans769ASum, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
 

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('contributionsChart').getContext('2d');
    const contributionsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Contributions', 'URA Members'],
            datasets: [{
                label: 'Count',
                data: [{{ $contributionCount }}, {{ $uraMembersCount }}],
                backgroundColor: ['#007bff', '#28a745']
            }]
        }
    });
</script>



</div>

<script>
    var fromAmount = <?php echo $fromAmount; ?>;
    var toAmount = <?php echo $toAmount; ?>;

    var options = {
        series: [fromAmount, toAmount],
        chart: {
            height: 400,
            type: 'donut',
            toolbar: {
                show: false
            }
        },
        labels: ['Decrease', 'Increase'],
        colors: ['#008FFB', '#FFB74D'],
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function (w) {
                                return fromAmount + toAmount;
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(1) + "%";
            }
        },
        legend: {
            position: 'bottom',
            fontSize: '14px'
        },
        stroke: {
            show: true,
            width: 2
        },
        responsive: [{
            breakpoint: 768,
            options: {
                chart: {
                    height: 320
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#statisticsDonutChart"), options);
    chart.render();
</script>



  </div>
  </div>
  </div>
<script>
  document.addEventListener("DOMContentLoaded", function () {
      // Generate gradient colors for charts
      function getGradient(ctx, color1, color2) {
          let gradient = ctx.createLinearGradient(0, 0, 0, 400);
          gradient.addColorStop(0, color1);
          gradient.addColorStop(1, color2);
          return gradient;
      }

      // Loan Category Chart
      var ctx1 = document.getElementById('loanCategoryChart').getContext('2d');
      var loanCategories = @json(array_keys($loanCategoryData->toArray()));
      var loanCounts = @json(array_values($loanCategoryData->toArray()));
      var gradient1 = getGradient(ctx1, "rgba(54, 162, 235, 0.8)", "rgba(54, 162, 235, 0.3)");

      new Chart(ctx1, {
          type: 'bar',
          data: {
              labels: loanCategories,
              datasets: [{
                  label: 'Loan Frequency',
                  data: loanCounts,
                  backgroundColor: gradient1,
                  borderColor: 'rgba(54, 162, 235, 1)',
                  borderWidth: 1,
                  borderRadius: 8
              }]
          },
          options: {
              responsive: true,
              scales: {
                  y: { beginAtZero: true }
              },
              plugins: {
                  legend: { display: false }
              }
          }
      });

      // Monthly Loan Applications Chart
      var ctx2 = document.getElementById('monthlyLoanChart').getContext('2d');
      var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      var monthlyCounts = @json($monthlyLoanData->toArray());
      var formattedData = months.map((_, index) => monthlyCounts[index + 1] || 0);
      var gradient2 = getGradient(ctx2, "rgba(255, 99, 132, 0.8)", "rgba(255, 99, 132, 0.3)");

      new Chart(ctx2, {
          type: 'bar',
          data: {
              labels: months,
              datasets: [{
                  label: 'Loan Applications',
                  data: formattedData,
                  backgroundColor: gradient2,
                  borderColor: 'rgba(255, 99, 132, 1)',
                  borderWidth: 1,
                  borderRadius: 8
              }]
          },
          options: {
              responsive: true,
              scales: {
                  y: { beginAtZero: true }
              },
              plugins: {
                  legend: { display: false }
              }
          }
      });
  });
</script>

@endsection
