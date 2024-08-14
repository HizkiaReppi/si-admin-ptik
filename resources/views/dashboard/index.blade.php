<x-dashboard-layout title="Dashboard">
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="card px-4 pt-4 pb-3 mb-4">
        <div class="row">
            <div class="col-md-3 col-6 mb-2">
                <div class="card p-1">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="menu-icon tf-icons fa-solid fa-chalkboard-user"></i>
                                <span class="fw-medium d-block mb-1">Total Dosen</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                    <a class="dropdown-item" href="{{ route('dashboard.lecturer.index') }}">Lihat
                                        Detail</a>
                                </div>
                            </div>
                        </div>
                        <h3 class="card-title mb-0 mt-3">{{ $totalLecturers }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card p-1">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="menu-icon tf-icons fa-solid fa-user"></i>
                                <span class="fw-medium d-block mb-1">Total Mahasiswa</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                    <a class="dropdown-item" href="{{ route('dashboard.student.index') }}">Lihat
                                        Detail</a>
                                </div>
                            </div>
                        </div>
                        <h3 class="card-title mb-0 mt-3">{{ $totalStudents }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card p-1">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="menu-icon tf-icons fa-solid fa-user"></i>
                                <span class="fw-medium d-block mb-1">Total Pengajuan Surat</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                    <a class="dropdown-item" href="{{ route('dashboard.submission.index') }}">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                        <h3 class="card-title mb-0 mt-3">{{ $totalSubmission }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card p-1">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="menu-icon tf-icons fa-solid fa-user"></i>
                                <span class="fw-medium d-block mb-1">Total Surat Selesai</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                    <a class="dropdown-item" href="{{ route('dashboard.submission.index') }}">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                        <h3 class="card-title mb-0 mt-3">{{ $totalSubmissionDone }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card px-4 pt-4 pb-3 mb-4">
        <div class="row">
            <div class="col-md-6 mt-md-3">
                <h6>Total Pengajuan Surat Per Bulan</h6>
                <div id="monthly-submissions"></div>
            </div>
            <div class="col-md-6 mt-md-3">
                <h6>Total Pengajuan Surat Per Status</h6>
                <div id="status-counts"></div>
            </div>
            <div class="col-md-12 mt-3">
                <h6>Total Pengajuan Surat Per Kategori</h6>
                <div id="category-counts"></div>
            </div>
        </div>
    </div>

    <div class="card px-4 pt-4 pb-3 mb-4">
        <div class="row">
            <div class="col-md-6 mt-md-3">
                <h6>Persebaran Mahasiswa per Angkatan</h6>
                <div id="student-batch"></div>
            </div>
            <div class="col-md-6 mt-md-3">
                <h6>Persebaran Mahasiswa per Konsentrasi</h6>
                <div id="student-concentration"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            try {
                const monthlySubmissionsResponse = await fetch('{{ route('dashboard.chart.monthlySubmissions') }}');
                const monthlySubmissionsData = await monthlySubmissionsResponse.json();
                const monthlySubmissionsOptions = {
                    chart: {
                        type: 'bar',
                        height: '100%',
                        width: '100%',
                        responsive: [{
                            breakpoint: 768,
                            options: {
                                chart: {
                                    width: '100%',
                                    height: '100%'
                                },
                                plotOptions: {
                                    bar: {
                                        horizontal: true
                                    }
                                }
                            }
                        }]
                    },
                    series: [{
                        name: 'Submissions',
                        data: monthlySubmissionsData.map(item => item.count)
                    }],
                    xaxis: {
                        categories: monthlySubmissionsData.map(item => item.month)
                    }
                };
                const monthlySubmissionsChart = new ApexCharts(document.querySelector("#monthly-submissions"), monthlySubmissionsOptions);
                monthlySubmissionsChart.render();

                const statusCountsResponse = await fetch('{{ route('dashboard.chart.statusCounts') }}');
                const statusCountsData = await statusCountsResponse.json();
                const statusCountsOptions = {
                    chart: {
                        type: 'donut',
                        height: '100%',
                        width: '100%',
                        toolbar: {
                            show: true
                        }
                    },
                    series: statusCountsData.map(item => item.count),
                    labels: statusCountsData.map(item => item.status),
                    responsive: [{
                        breakpoint: 768,
                        options: {
                            chart: {
                                width: '100%',
                                height: '100%'
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };
                const statusCountsChart = new ApexCharts(document.querySelector("#status-counts"), statusCountsOptions);
                statusCountsChart.render();

                const categoryCountsResponse = await fetch('{{ route('dashboard.chart.categoryCounts') }}');
                const categoryCountsData = await categoryCountsResponse.json();
                const categoryCountsOptions = {
                    chart: {
                        type: 'pie',
                        height: '100%',
                        width: '100%',
                        toolbar: {
                            show: true
                        }
                    },
                    series: categoryCountsData.map(item => item.count),
                    labels: categoryCountsData.map(item => item.category.name),
                    responsive: [{
                        breakpoint: 768,
                        options: {
                            chart: {
                                width: '100%',
                                height: '100%'
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };
                const categoryCountsChart = new ApexCharts(document.querySelector("#category-counts"), categoryCountsOptions);
                categoryCountsChart.render();

                const batchResponse = await fetch('{{ route('dashboard.chart.studentBatch') }}');
                const batchData = await batchResponse.json();

                const batchOptions = {
                    chart: {
                        type: 'bar',
                        toolbar: {
                            show: true
                        }
                    },
                    series: [{
                        name: 'Total Mahasiswa',
                        data: batchData.map(item => item.count)
                    }],
                    xaxis: {
                        categories: batchData.map(item => item.batch)
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                        }
                    },
                    dataLabels: {
                        enabled: true
                    },
                };

                const batchChart = new ApexCharts(document.querySelector("#student-batch"), batchOptions);
                batchChart.render();

                const concentrationResponse = await fetch('{{ route('dashboard.chart.studentConcentration') }}');
                const concentrationData = await concentrationResponse.json();

                const concentrationOptions = {
                    chart: {
                        type: 'pie',
                        toolbar: {
                            show: true
                        }
                    },
                    series: concentrationData.map(item => item.count),
                    labels: concentrationData.map(item => item.concentration),
                    dataLabels: {
                        enabled: true
                    },
                };

                const concentrationChart = new ApexCharts(document.querySelector("#student-concentration"), concentrationOptions);
                concentrationChart.render();

            } catch (error) {
                console.error('Error fetching chart data:', error);
            }
        });
    </script>


</x-dashboard-layout>
