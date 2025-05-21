@extends('layouts.admin')

@section('page-title')
    {{ __('Dashboard') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Home') }}</li>
@endsection
@section('content')
    <div class="dashboard-page mt-md-3 mt-2">
        <div class="dashboard-page-wrp dashboard-page-card row-gap-1 row mb-4">
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card total-category">
                    <div class="card-body d-flex flex-column justify-content-between gap-3">
                        <div class="bottom-img">
                            <svg width="128" height="95" viewBox="0 0 128 95" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="102.001" height="83.0013" rx="6"
                                    transform="matrix(0.746641 -0.667026 0.686312 0.725658 -2 110.038)" fill="#FF3A6E"
                                    fill-opacity="0.10" />
                                <rect width="75.3406" height="61.3067" rx="6"
                                    transform="matrix(0.746641 -0.667026 0.686312 0.725658 88 48.2542)" fill="#FF3A6E"
                                    fill-opacity="0.10" />
                            </svg>
                        </div>
                        <div class="card-icon-wrp d-flex flex-wrap justify-content-between gap-2">
                            <div class="card-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.1668 10.8333C19.3752 10.8333 19.5836 10.6249 19.5836 10.4165V7.9165C19.5836 7.2081 19.042 6.6665 18.3336 6.6665H15V18.3333H18.3332C19.0416 18.3333 19.5832 17.7917 19.5832 17.0833V14.5833C19.583 14.4728 19.539 14.367 19.4609 14.2888C19.3827 14.2107 19.2769 14.1667 19.1664 14.1665C18.7288 14.1597 18.3115 13.9811 18.0045 13.6694C17.6975 13.3575 17.5254 12.9375 17.5254 12.4999C17.5254 12.0623 17.6975 11.6423 18.0045 11.3305C18.3115 11.0186 18.7293 10.84 19.1668 10.8333Z"
                                        fill="white" />
                                    <path
                                        d="M18.8747 5.4166C19.0832 5.3334 19.2079 5.0834 19.1247 4.875L18.2912 2.4998C18.0827 1.833 17.3312 1.4998 16.7079 1.7082L4.87476 5.7914H18.208C18.4056 5.62694 18.6316 5.49993 18.8747 5.4166Z"
                                        fill="white" />
                                    <path
                                        d="M0.416748 7.91699V10.417C0.416748 10.667 0.583548 10.8338 0.833548 10.8338C1.05458 10.8304 1.27409 10.871 1.47929 10.9532C1.68449 11.0354 1.87129 11.1576 2.02881 11.3127C2.18633 11.4678 2.31142 11.6527 2.39681 11.8565C2.4822 12.0605 2.52616 12.2794 2.52616 12.5004C2.52616 12.7215 2.4822 12.9403 2.39681 13.1442C2.31142 13.3481 2.18633 13.533 2.02881 13.6881C1.87129 13.8432 1.68449 13.9654 1.47929 14.0476C1.27409 14.1299 1.05458 14.1705 0.833548 14.167C0.583548 14.167 0.416748 14.3338 0.416748 14.5838V17.0838C0.416748 17.7922 0.958348 18.3338 1.66675 18.3338H14.1667V6.66699H1.66675C0.959948 6.66699 0.416748 7.20859 0.416748 7.91699ZM5.41675 10.0002H7.91675C8.16675 10.0002 8.33358 10.167 8.33358 10.417C8.33358 10.667 8.16675 10.8338 7.91675 10.8338H5.41675C5.16675 10.8338 4.99995 10.667 4.99995 10.417C4.99995 10.167 5.16675 10.0002 5.41675 10.0002ZM5.41675 12.0834H10.8332C11.0832 12.0834 11.2499 12.2502 11.2499 12.5002C11.2499 12.7502 11.0832 12.917 10.8332 12.917H5.41675C5.16675 12.917 4.99995 12.7502 4.99995 12.5002C4.99995 12.2502 5.16675 12.0834 5.41675 12.0834ZM5.41675 14.1666H10.8332C11.0832 14.1666 11.2499 14.3334 11.2499 14.5834C11.2499 14.8334 11.0832 15.0002 10.8332 15.0002H5.41675C5.16675 15.0002 4.99995 14.8334 4.99995 14.5834C4.99995 14.3334 5.16675 14.1666 5.41675 14.1666Z"
                                        fill="white" />
                                    <rect x="4.16675" y="9.1665" width="7.5" height="6.66667" fill="white" />
                                    <path
                                        d="M11 11.4732C11.0002 11.5353 10.9872 11.5969 10.9619 11.6543C10.9365 11.7117 10.8993 11.7639 10.8524 11.8077L7.67258 14.791C7.52954 14.9248 7.3358 15 7.13382 15C6.93184 15 6.73809 14.9248 6.59506 14.791L5.14896 13.4343C5.10186 13.3904 5.06446 13.3382 5.0389 13.2808C5.01333 13.2233 5.00011 13.1617 5 13.0994C4.99988 13.0371 5.01287 12.9754 5.03822 12.9179C5.06356 12.8603 5.10077 12.808 5.1477 12.764C5.19463 12.7199 5.25037 12.685 5.31172 12.6613C5.37306 12.6375 5.43881 12.6253 5.50518 12.6254C5.57156 12.6255 5.63725 12.6379 5.69851 12.6619C5.75976 12.6859 5.81536 12.721 5.86213 12.7652L7.13382 13.9583L10.1392 11.1386C10.2097 11.0724 10.2996 11.0274 10.3974 11.0091C10.4953 10.9908 10.5966 11.0002 10.6887 11.036C10.7809 11.0718 10.8596 11.1325 10.9151 11.2103C10.9705 11.2881 11.0001 11.3796 11 11.4732Z"
                                        fill="#FF3A6E" />
                                </svg>
                            </div>
                            <h4 class="mb-0 h3">{{ $totalAssignTickets }}</h4>
                        </div>
                        <div class="card-content">
                            <h3 class="h4 mb-0">{{ __('Total Assign Tickets') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card open-tickets">
                    <div class="card-body d-flex flex-column justify-content-between gap-3">
                        <div class="bottom-img">
                            <svg width="128" height="95" viewBox="0 0 128 95" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="102.001" height="83.0013" rx="6"
                                    transform="matrix(0.746641 -0.667026 0.686312 0.725658 -2 110.038)" fill="#FF3A6E"
                                    fill-opacity="0.10" />
                                <rect width="75.3406" height="61.3067" rx="6"
                                    transform="matrix(0.746641 -0.667026 0.686312 0.725658 88 48.2542)" fill="#FF3A6E"
                                    fill-opacity="0.10" />
                            </svg>
                        </div>
                        <div class="card-icon-wrp d-flex flex-wrap justify-content-between gap-2">
                            <div class="card-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20.0085 14.7976L19.9992 12.4907C19.3131 12.4907 18.7247 12.2456 18.2346 11.7555C17.7444 11.2653 17.4995 10.6771 17.4995 9.99112C17.4994 9.3047 17.7446 8.71676 18.2348 8.22654C18.7251 7.73629 19.3133 7.49132 19.9995 7.49119L19.9993 5.19345C19.999 4.7399 19.8352 4.34937 19.5076 4.02174C19.1798 3.69392 18.7892 3.53017 18.3359 3.53004L1.67359 3.5208C1.22 3.52084 0.829284 3.68488 0.501785 4.01238C0.174028 4.34013 0.0102473 4.73059 0.0101827 5.18421L0.000878345 7.4911C0.687037 7.49122 1.27534 7.73625 1.76549 8.22641C2.25561 8.71653 2.50077 9.30451 2.50064 9.99086C2.50064 10.677 2.25558 11.2652 1.7654 11.7553C1.27521 12.2455 0.687328 12.4905 0.000912231 12.4906L0.000975283 14.8066C0.00100664 15.2601 0.16495 15.6507 0.492739 15.9785C0.82056 16.3063 1.21118 16.4703 1.66448 16.4702L18.3451 16.461C18.7985 16.461 19.1889 16.2971 19.5167 15.9692C19.8445 15.6414 20.0084 15.251 20.0085 14.7976ZM15.6797 14.145L4.32056 14.1448C4.0999 14.1451 3.90695 14.0624 3.74143 13.8968C3.57607 13.7315 3.49331 13.5386 3.49354 13.3178L3.49318 6.66404C3.49331 6.43105 3.57295 6.23508 3.73213 6.0759C3.89144 5.9166 4.0876 5.83677 4.32049 5.83673L15.6797 5.83676C15.9004 5.83683 16.0936 5.91956 16.259 6.08501C16.4243 6.25027 16.507 6.44338 16.507 6.66407L16.5069 13.3178C16.507 13.5509 16.4273 13.7469 16.268 13.9062C16.1087 14.0653 15.9128 14.145 15.6797 14.145Z"
                                    fill="white" />
                                <path d="M15.2567 7.08726L15.2569 12.8956H4.74315L4.74315 7.08742L15.2567 7.08726Z"
                                    fill="white" />
                            </svg>

                            </div>
                            <h4 class="mb-0 h3">{{ $openTicket }}</h4>
                        </div>
                        <div class="card-content">
                            <h3 class="h4 mb-0">{{ __('open tickets') }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <div class="card close-tickets">
                    <div class="card-body d-flex flex-column justify-content-between gap-3">
                        <div class="bottom-img">
                            <svg width="128" height="95" viewBox="0 0 128 95" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="102.001" height="83.0013" rx="6"
                                    transform="matrix(0.746641 -0.667026 0.686312 0.725658 -2 110.038)" fill="#FF3A6E"
                                    fill-opacity="0.10" />
                                <rect width="75.3406" height="61.3067" rx="6"
                                    transform="matrix(0.746641 -0.667026 0.686312 0.725658 88 48.2542)" fill="#FF3A6E"
                                    fill-opacity="0.10" />
                            </svg>
                        </div>
                        <div class="card-icon-wrp d-flex flex-wrap justify-content-between gap-2">

                            <div class="card-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19.1668 10.8333C19.3752 10.8333 19.5836 10.6249 19.5836 10.4165V7.9165C19.5836 7.2081 19.042 6.6665 18.3336 6.6665H15V18.3333H18.3332C19.0416 18.3333 19.5832 17.7917 19.5832 17.0833V14.5833C19.583 14.4728 19.539 14.367 19.4609 14.2888C19.3827 14.2107 19.2769 14.1667 19.1664 14.1665C18.7288 14.1597 18.3115 13.9811 18.0045 13.6694C17.6975 13.3575 17.5254 12.9375 17.5254 12.4999C17.5254 12.0623 17.6975 11.6423 18.0045 11.3305C18.3115 11.0186 18.7293 10.84 19.1668 10.8333Z"
                                    fill="white" />
                                <path
                                    d="M18.8745 5.41684C19.0829 5.33364 19.2077 5.08364 19.1245 4.87524L18.2909 2.50004C18.0825 1.83324 17.3309 1.50004 16.7077 1.70844L4.87451 5.79164H18.2078C18.4053 5.62718 18.6313 5.50017 18.8745 5.41684Z"
                                    fill="white" />
                                <path
                                    d="M0.416504 7.91699V10.417C0.416504 10.667 0.583304 10.8338 0.833304 10.8338C1.05434 10.8304 1.27385 10.871 1.47905 10.9532C1.68425 11.0354 1.87105 11.1576 2.02856 11.3127C2.18609 11.4678 2.31118 11.6527 2.39656 11.8565C2.48195 12.0605 2.52592 12.2794 2.52592 12.5004C2.52592 12.7215 2.48195 12.9403 2.39656 13.1442C2.31118 13.3481 2.18609 13.533 2.02856 13.6881C1.87105 13.8432 1.68425 13.9654 1.47905 14.0476C1.27385 14.1299 1.05434 14.1705 0.833304 14.167C0.583304 14.167 0.416504 14.3338 0.416504 14.5838V17.0838C0.416504 17.7922 0.958104 18.3338 1.6665 18.3338H14.1665V6.66699H1.6665C0.959704 6.66699 0.416504 7.20859 0.416504 7.91699ZM5.4165 10.0002H7.9165C8.1665 10.0002 8.33334 10.167 8.33334 10.417C8.33334 10.667 8.1665 10.8338 7.9165 10.8338H5.4165C5.1665 10.8338 4.9997 10.667 4.9997 10.417C4.9997 10.167 5.1665 10.0002 5.4165 10.0002ZM5.4165 12.0834H10.8329C11.0829 12.0834 11.2497 12.2502 11.2497 12.5002C11.2497 12.7502 11.0829 12.917 10.8329 12.917H5.4165C5.1665 12.917 4.9997 12.7502 4.9997 12.5002C4.9997 12.2502 5.1665 12.0834 5.4165 12.0834ZM5.4165 14.1666H10.8329C11.0829 14.1666 11.2497 14.3334 11.2497 14.5834C11.2497 14.8334 11.0829 15.0002 10.8329 15.0002H5.4165C5.1665 15.0002 4.9997 14.8334 4.9997 14.5834C4.9997 14.3334 5.1665 14.1666 5.4165 14.1666Z"
                                    fill="white" />
                                <rect x="4.1665" y="9.16675" width="7.5" height="6.66667" fill="white" />
                                <g clip-path="url(#clip0_1363_47)">
                                    <path class="theme-color"
                                        d="M8.88419 12.5003L10.7536 10.6308C10.805 10.5794 10.8334 10.5107 10.8335 10.4375C10.8335 10.3642 10.8051 10.2955 10.7536 10.2441L10.5898 10.0803C10.5383 10.0287 10.4696 10.0005 10.3963 10.0005C10.3232 10.0005 10.2545 10.0287 10.203 10.0803L8.33358 11.9497L6.46407 10.0803C6.41264 10.0287 6.34394 10.0005 6.27069 10.0005C6.19752 10.0005 6.12882 10.0287 6.0774 10.0803L5.9135 10.2441C5.80683 10.3508 5.80683 10.5243 5.9135 10.6308L7.78297 12.5003L5.9135 14.3698C5.86203 14.4213 5.8337 14.49 5.8337 14.5632C5.8337 14.6364 5.86203 14.705 5.9135 14.7565L6.07736 14.9203C6.12878 14.9719 6.19752 15.0002 6.27065 15.0002C6.3439 15.0002 6.4126 14.9719 6.46402 14.9203L8.33354 13.0509L10.203 14.9203C10.2545 14.9719 10.3231 15.0002 10.3963 15.0002H10.3964C10.4696 15.0002 10.5383 14.9719 10.5898 14.9203L10.7536 14.7565C10.805 14.7051 10.8334 14.6364 10.8334 14.5632C10.8334 14.49 10.805 14.4213 10.7536 14.3698L8.88419 12.5003Z"
                                        fill="#FFA21D" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_1363_47">
                                        <rect width="5" height="5" fill="white" transform="translate(5.8335 10)" />
                                    </clipPath>
                                </defs>
                            </svg>
                            </div>
                            <h4 class="mb-0 h3">{{ $closeTickets }}</h4>
                        </div>
                        <div class="card-content">
                            <h3 class="h4 mb-0">{{ __('close tickets') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row d-flex">
        <div class="col-xxl-4 col-md-6 d-flex">
            <div class="card w-100">
                <div class="card-header">
                    <h5>{{ __('Tickets by Category') }}</h5>
                </div>
                <div class="card-body">
                    <div id="categoryPie" class="h-100 d-flex align-items-center"></div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-md-6 d-flex">
            <div class="card w-100">
                <div class="card-header">
                    <h5>{{ __('Tickets by Status') }}</h5>
                </div>
                <div class="card-body">
                    <div id="statusPie" class="h-100 d-flex align-items-center justify-content-center"></div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 d-flex">
            <div class="card w-100">
                <div class="card-header">
                    <h5>{{ __('Tickets by Priority') }}</h5>
                </div>
                <div class="card-body">
                    <div id="priorityPie" class="h-100 d-flex align-items-center"></div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('This Year Tickets') }}</h5>
                </div>
                <div class="card-body">
                    <div id="chartBar"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        $('.cp_link').on('click', function () {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Success', '{{ __('Link Copy on Clipboard ') }}', 'success')
        });
    </script>

    <script>
        // Category wise total number of tickets
        (function () {
            var options = {
                series: {!!json_encode($chartData['value']) !!},
                colors: {!!json_encode($chartData['color']) !!},
                chart: {
                    height: 300,
                    type: 'radialBar',
                },
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            name: {
                                fontSize: '22px',
                            },
                            value: {
                                fontSize: '16px',
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function (w) {
                                    var totalSum = w.globals.initialSeries.reduce(function (acc, value) {
                                        return acc + value;
                                    }, 0);
                                    return totalSum
                                }
                            }
                        }
                    }
                },
                labels: {!!json_encode($chartData['name']) !!},
                responsive: [{
                    breakpoint: 420,
                    options: {
                        chart: {
                            height: 250
                        }
                    }
                },]
            };

            var chart = new ApexCharts(document.querySelector("#categoryPie"), options);
            chart.render();

        })();

        // Ticket Status Wise Total Number of Tickets
        (function () {
            var options = {
                series: {!!json_encode($statusData['value']) !!},
                chart: {
                    width: 380,
                    type: 'polarArea'
                },
                labels: {!!json_encode($statusData['name']) !!},
                fill: {
                    opacity: 1
                },
                stroke: {
                    width: 1,
                    colors: undefined
                },
                colors: ['#0CAF60', '#FFA21D', '#FF3A6E'],
                yaxis: {
                    show: false
                },
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    polarArea: {
                        rings: {
                            strokeWidth: 0
                        },
                        spokes: {
                            strokeWidth: 0
                        },
                    }
                },
                theme: {
                    monochrome: {
                        enabled: false,
                        shadeTo: 'light',
                        shadeIntensity: 0.6
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#statusPie"), options);
            chart.render();
        })();


        // Ticket Prioritywise total number of tickets
        (function () {
            var priorityPieOptions = {
                chart: {
                    height: 200,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!!json_encode($priorityData['value']) !!},
                labels: {!!json_encode($priorityData['name']) !!},
                // colors: ['#FF5733', '#FFC300', '#DAF7A6', '#C70039'],
                legend: {
                    show: true
                }
            };
            var priorityPieChart = new ApexCharts(document.querySelector("#priorityPie"), priorityPieOptions);
            priorityPieChart.render();
        })();

        // Show the total number of tickets Monthwise
        (function () {
            var chartBarOptions = {
                series: [{
                    name: '{{ __('Tickets ') }}',
                    data: {!!json_encode(array_values($monthData)) !!}
                },],

                chart: {
                    height: 300,
                    type: 'area',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: {!!json_encode(array_keys($monthData)) !!},
                    title: {
                        text: '{{ __('Months') }}'
                    }
                },
                colors: ['#ffa21d', '#FF3A6E'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                markers: {
                    size: 4,
                    colors: ['#ffa21d', '#FF3A6E'],
                    opacity: 0.9,
                    strokeWidth: 2,
                    hover: {
                        size: 7,
                    }
                },
                yaxis: {
                    title: {
                        text: '{{ __('Tickets ') }}'
                    },
                    tickAmount: 3,
                    min: 10,
                    max: 70,
                }
            };
            var arChart = new ApexCharts(document.querySelector("#chartBar"), chartBarOptions);
            arChart.render();
        })();
    </script>
@endpush