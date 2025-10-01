@extends('layouts.app')

@section('content')
<div class="container">
    <div class="main-body">
        <div class="row justify-content-center">
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-light">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center mb-4">
                            <iconify-icon icon="mingcute:user-follow-fill" class="icon text-primary text-6xl p-5 card-icon rounded-circle bg-gradient-primary d-flex justify-content-center align-items-center"></iconify-icon>
                        </div>
                        <h4 class="text-uppercase font-weight-bold mb-2 text-primary">{{ $user->name }}</h4>
                        <p class="text-secondary text-uppercase font-weight-bold">{{ $user->designation }}</p>
                        <p class="text-muted font-size-sm mb-3 text-uppercase text-success">
                            <strong>{{ $user->branch->name ?? 'N/A' }}</strong>, <strong>{{ $user->region->name ?? 'N/A' }}</strong>
                        </p>
                        <hr>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Email</span>
                                <span class="text-secondary">{{ $user->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Phone Number</span>
                                <span class="text-secondary">{{ $user->phone_number }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Rank</span>
                                <span class="text-secondary">{{ $user->rank->name ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Status</span>
                                <span class="text-secondary">{{ $user->status }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Role</span>
                                <span class="text-secondary">
                                    {{ $user->getRoleNames()->isNotEmpty() ? $user->getRoleNames()->join(', ') : 'No roles assigned' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header  text-primary">
                        <h6 class="font-weight-bold text-uppercase text-primary">LOCATION</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Branch</span>
                                <span class="text-secondary font-weight-bold">{{ $user->branch->name ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Region</span>
                                <span class="text-secondary font-weight-bold">{{ $user->region->name ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Department</span>
                                <span class="text-secondary font-weight-bold">{{ $user->department->name ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>District</span>
                                <span class="text-secondary font-weight-bold">{{ $user->district->name ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Command</span>
                                <span class="text-secondary font-weight-bold">{{ $user->command->name ?? 'N/A' }}</span>
                            </li>
                        </ul>
                        <div class="row mt-4">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Back to Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header  text-primary">
                        <h6 class="font-weight-bold text-uppercase text-primary">Access History</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Success Login</span>
                                <span class="text-secondary">
                                    {{ $user->first_login ?  $user->first_login : 'Never' }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Last Password Change</span>
                                <span class="text-secondary">
                                    {{ $user->last_password_change ? $user->last_password_change->format('Y-m-d H:i:s') . ' (' . $user->last_password_change->diffForHumans() . ')' : 'Never' }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Last Login</span>
                                <span class="text-secondary">
                                    {{ $user->last_login ? $user->last_login->format('Y-m-d H:i:s') . ' (' . $user->last_login->diffForHumans() . ')' : 'Never' }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Login Attempts Failure</span>
                                <span class="text-secondary">{{ $user->login_attempts }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection