@extends('layouts.app')

@section('title', 'Maombi ya Makato')
@section('page-title', 'Maombi ya Makato')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Maombi ya Makato</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Dhibiti maombi ya kuongeza na kupunguza makato</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('deductions.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                <span class="material-icons mr-2">add</span>
                Ombi Jipya
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-yellow-600">pending</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Inasubiri</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $analytics['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-green-600">check_circle</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Imeidhinishwa</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $analytics['approved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-red-600">cancel</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Imekataliwa</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $analytics['rejected'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-blue-600">description</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumla</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $analytics['total'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Search Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <form method="GET" action="{{ route('deductions.index') }}" id="searchForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-4">
                <!-- Check Number Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Check Number</label>
                    <input type="text" 
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Tafuta kwa namba ya cheki..."
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hali</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Hali Zote</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Inasubiri</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Imeidhinishwa</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Imekataliwa</option>
                    </select>
                </div>

                <!-- User Name Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Aliesajili</label>
                    <input type="text" 
                           name="user_name"
                           value="{{ request('user_name') }}"
                           placeholder="Jina la msajili..."
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <!-- Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tarehe</label>
                    <input type="date" 
                           name="date_filter"
                           value="{{ request('date_filter') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <span class="material-icons mr-1">search</span>
                        Tafuta
                    </button>
                    
                    <a href="{{ route('deductions.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <span class="material-icons mr-1">clear</span>
                        Ondoa
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Batch Actions -->
    @php
        $user = Auth::user();
        $userRole = $user && $user->employee && $user->employee->role ? strtolower($user->employee->role->role) : '';
        $canManageApplications = in_array($userRole, ['manager', 'admin']);
    @endphp
    
    @if($canManageApplications)
    <div id="batchActions" class="hidden bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-700 rounded-xl p-6">
        <div class="space-y-4">
            <!-- Selection Info -->
            <div class="flex items-center justify-between">
                <span class="text-sm text-primary-700 dark:text-primary-300 font-medium">
                    <span id="selectedCount">0</span> maombi yamechaguliwa
                </span>
                <button onclick="clearSelection()" 
                        class="text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                    <span class="material-icons mr-1 text-sm">clear</span>
                    Ondoa Chaguo
                </button>
            </div>
            
            <!-- Action Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <!-- Export CSV + Approve -->
                <button onclick="exportAndApprove('csv')" 
                        class="px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        id="exportCSVBtn">
                    <span class="material-icons mr-1">download_done</span>
                    Export EXCEL + Idhinisha
                </button>
                
                <!-- Export Excel + Approve -->
                <button onclick="exportAndApprove('excel')" 
                        class="px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        id="exportExcelBtn">
                    <span class="material-icons mr-1">download_done</span>
                    Export HTML + Idhinisha
                </button>
                
                <!-- View Selected -->
                <button onclick="showSelectedItemsSummary()" 
                        class="px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
                        id="viewSelectedBtn">
                    <span class="material-icons mr-1">visibility</span>
                    Ona Yaliyochaguliwa
                </button>
                
                <!-- Reject -->
                <button onclick="showBatchRejectModal()" 
                        class="px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        id="batchRejectBtn">
                    <span class="material-icons mr-1">cancel</span>
                    Kataa Maombi
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Applications Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Orodha ya Maombi</h3>
                
                @if($canManageApplications && $applications->where('status', 'pending')->count() > 0)
                <div class="flex items-center space-x-2">
                    <input type="checkbox" 
                           id="selectAll" 
                           onchange="toggleSelectAll()"
                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                    <label for="selectAll" class="text-sm text-gray-600 dark:text-gray-400">Chagua Vyote</label>
                </div>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        @if($canManageApplications)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <span class="material-icons text-sm">checklist</span>
                        </th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Majina
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aliesajili
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Mahali
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Mchango wa Sasa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kiasi Kipya
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Utofauti
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Hali
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tarehe
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Vitendo
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($applications as $application)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        @if($canManageApplications)
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($application->status === 'pending')
                            <input type="checkbox" 
                                   class="application-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                   value="{{ $application->id }}"
                                   onchange="updateBatchActions()">
                            @endif
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                   {{ $application->names }} 
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $application->dept_name }} [ {{ $application->check_number }} ]
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center shadow-md mr-3">
                                    <span class="material-icons text-white text-sm">person</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $application->user->name ?? 'Haijulikani' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $application->user ? $application->user->email : 'Haijulikani' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center">
                                    <span class="material-icons text-gray-400 mr-1 text-sm">business</span>
                                    {{ $application->user->employee->district->branch->name ?? 'Haijulikani' }}
                                </div>
                                <div class="flex items-center mt-1">
                                    <span class="material-icons text-gray-400 mr-1 text-sm">location_city</span>
                                    {{ $application->district_name }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                Tsh {{ $application->formatted_current_contribution }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-primary-600">
                                Tsh {{ $application->formatted_new_contribution }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium {{ $application->difference_amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $application->difference_amount > 0 ? '+' : '' }}Tsh {{ $application->formatted_difference }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                                {{ $application->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                <span class="material-icons text-xs mr-1">
                                    {{ $application->status === 'pending' ? 'pending' : '' }}
                                    {{ $application->status === 'approved' ? 'check_circle' : '' }}
                                    {{ $application->status === 'rejected' ? 'cancel' : '' }}
                                </span>
                                {{ $application->status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $application->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('deductions.show', $application) }}" 
                                   class="text-primary-600 hover:text-primary-700 transition-colors"
                                   title="Ona Maelezo">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                
                                @if($canManageApplications)
                                    @if($application->canApprove())
                                    <button onclick="approveApplication({{ $application->id }})"
                                            class="text-green-600 hover:text-green-700 transition-colors"
                                            title="Idhinisha">
                                        <span class="material-icons text-sm">check_circle</span>
                                    </button>
                                    @endif
                                    
                                    @if($application->canReject())
                                    <button onclick="rejectApplication({{ $application->id }})"
                                            class="text-red-600 hover:text-red-700 transition-colors"
                                            title="Kataa">
                                        <span class="material-icons text-sm">cancel</span>
                                    </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $canManageApplications ? '10' : '9' }}" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center">
                                <span class="material-icons text-gray-400 text-4xl mb-2">description</span>
                                <p class="text-gray-500 dark:text-gray-400">Hakuna maombi yoyote kwa sasa</p>
                                <a href="{{ route('deductions.create') }}" 
                                   class="mt-2 text-primary-600 hover:text-primary-700 transition-colors">
                                    Wasilisha ombi la kwanza
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($applications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $applications->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-data="{ show: false, applicationId: null, reason: '' }">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="material-icons text-red-600">warning</span>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Kataa Ombi
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Je, una uhakika unataka kukataa ombi hili? Tafadhali toa sababu za kukataa.
                            </p>
                            <textarea x-model="reason" 
                                      rows="4" 
                                      class="mt-3 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Andika sababu za kukataa ombi..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button @click="submitRejection()" 
                        :disabled="!reason.trim()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Kataa Ombi
                </button>
                <button @click="closeRejectModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Ghairi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Batch Reject Modal -->
<div id="batchRejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="material-icons text-red-600">warning</span>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Kataa Maombi Yaliyochaguliwa
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                Je, una uhakika unataka kukataa maombi yote yaliyochaguliwa? Tafadhali toa sababu za kukataa.
                            </p>
                            
                            <textarea id="batchRejectionReason" 
                                      rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Andika sababu za kukataa maombi..."
                                      required></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="submitBatchRejection()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submitBatchRejectBtn">
                    Kataa Maombi Yote
                </button>
                <button onclick="closeBatchRejectModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Ghairi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced selection management with cross-page functionality

// Global variables
let selectedApplications = new Set();
let allApplicationsData = new Map();
const STORAGE_KEY = 'deduction_selections';

let rejectModalData = {
    show: false,
    applicationId: null,
    reason: ''
};

// Load selections from sessionStorage on page load
function loadSelectionsFromStorage() {
    try {
        const stored = sessionStorage.getItem(STORAGE_KEY);
        if (stored) {
            const storedData = JSON.parse(stored);
            selectedApplications = new Set(storedData.selections || []);
            
            if (storedData.applicationsData) {
                allApplicationsData = new Map(storedData.applicationsData);
            }
        }
    } catch (error) {
        console.error('Error loading selections from storage:', error);
        selectedApplications = new Set();
        allApplicationsData = new Map();
    }
}

// Save selections to sessionStorage
function saveSelectionsToStorage() {
    try {
        const dataToStore = {
            selections: Array.from(selectedApplications),
            applicationsData: Array.from(allApplicationsData.entries()),
            timestamp: Date.now()
        };
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(dataToStore));
    } catch (error) {
        console.error('Error saving selections to storage:', error);
    }
}

// Clear storage
function clearSelectionsStorage() {
    try {
        sessionStorage.removeItem(STORAGE_KEY);
    } catch (error) {
        console.error('Error clearing selections storage:', error);
    }
}

// Store current page application data
function storeCurrentPageApplications() {
    document.querySelectorAll('.application-checkbox').forEach(checkbox => {
        const applicationId = checkbox.value;
        const row = checkbox.closest('tr');
        if (row) {
            const nameCell = row.querySelector('td:nth-child(' + ({{ $canManageApplications ? '2' : '1' }}) + ')');
            const statusCell = row.querySelector('td:nth-child(' + ({{ $canManageApplications ? '8' : '7' }}) + ')');
            
            if (nameCell && statusCell) {
                const applicationData = {
                    id: applicationId,
                    name: nameCell.querySelector('.text-sm.font-medium')?.textContent.trim() || '',
                    checkNumber: nameCell.querySelector('.text-sm.text-gray-500')?.textContent.trim() || '',
                    status: statusCell.querySelector('.inline-flex')?.textContent.trim() || ''
                };
                
                allApplicationsData.set(applicationId, applicationData);
            }
        }
    });
    saveSelectionsToStorage();
}

// Enhanced updateBatchActions function
function updateBatchActions() {
    const checkboxes = document.querySelectorAll('.application-checkbox:checked');
    const currentPageCount = checkboxes.length;
    
    // Update selectedApplications set for current page
    const currentPageApplications = new Set();
    document.querySelectorAll('.application-checkbox').forEach(cb => {
        currentPageApplications.add(cb.value);
        if (cb.checked) {
            selectedApplications.add(cb.value);
        } else {
            selectedApplications.delete(cb.value);
        }
    });
    
    // Store current page data
    storeCurrentPageApplications();
    
    const totalSelectedCount = selectedApplications.size;
    const batchActions = document.getElementById('batchActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (totalSelectedCount > 0) {
        batchActions.classList.remove('hidden');
        selectedCount.textContent = totalSelectedCount;
        enableBatchActionButtons(true);
        
        // Update the counter text to show cross-page selections
        if (totalSelectedCount !== currentPageCount) {
            selectedCount.innerHTML = `${totalSelectedCount} <span class="text-xs">(${currentPageCount} kwenye ukurasa huu)</span>`;
        }
    } else {
        batchActions.classList.add('hidden');
        enableBatchActionButtons(false);
    }
    
    // Update select all checkbox status
    updateSelectAllCheckbox();
    
    // Save to storage
    saveSelectionsToStorage();
}

// Enhanced select all functionality
function updateSelectAllCheckbox() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const allCheckboxes = document.querySelectorAll('.application-checkbox');
    const checkedCheckboxes = document.querySelectorAll('.application-checkbox:checked');
    
    if (selectAllCheckbox && allCheckboxes.length > 0) {
        const allCurrentPageSelected = allCheckboxes.length === checkedCheckboxes.length;
        const someCurrentPageSelected = checkedCheckboxes.length > 0;
        
        selectAllCheckbox.checked = allCurrentPageSelected;
        selectAllCheckbox.indeterminate = someCurrentPageSelected && !allCurrentPageSelected;
        
        // Update label to show cross-page selection status
        const selectAllLabel = document.querySelector('label[for="selectAll"]');
        if (selectAllLabel && selectedApplications.size > checkedCheckboxes.length) {
            selectAllLabel.innerHTML = `Chagua Vyote <span class="text-xs text-primary-600">(${selectedApplications.size} zimechaguliwa)</span>`;
        } else if (selectAllLabel) {
            selectAllLabel.textContent = 'Chagua Vyote';
        }
    }
}

// Enhanced toggleSelectAll function
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.application-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
        if (selectAll.checked) {
            selectedApplications.add(checkbox.value);
        } else {
            selectedApplications.delete(checkbox.value);
        }
    });
    
    updateBatchActions();
}

// Enhanced clearSelection function
function clearSelection() {
    selectedApplications.clear();
    allApplicationsData.clear();
    clearSelectionsStorage();
    
    document.querySelectorAll('.application-checkbox').forEach(cb => cb.checked = false);
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
    }
    
    // Reset select all label
    const selectAllLabel = document.querySelector('label[for="selectAll"]');
    if (selectAllLabel) {
        selectAllLabel.textContent = 'Chagua Vyote';
    }
    
    updateBatchActions();
}

// Add function to show selected items from all pages
function showSelectedItemsSummary() {
    if (selectedApplications.size === 0) {
        showToast('Hakuna maombi yaliyochaguliwa', 'info');
        return Promise.resolve();
    }
    
    let summaryHtml = '<div class="max-h-60 overflow-y-auto"><ul class="space-y-1">';
    
    selectedApplications.forEach(appId => {
        const appData = allApplicationsData.get(appId);
        if (appData) {
            summaryHtml += `<li class="text-sm p-2 bg-gray-50 rounded">
                <strong>${appData.name}</strong><br>
                <span class="text-gray-600">${appData.checkNumber}</span>
                <span class="ml-2 text-xs bg-blue-100 px-2 py-1 rounded">${appData.status}</span>
            </li>`;
        } else {
            summaryHtml += `<li class="text-sm p-2 bg-gray-50 rounded">ID: ${appId}</li>`;
        }
    });
    
    summaryHtml += '</ul></div>';
    
    return Swal.fire({
        title: `Maombi Yaliyochaguliwa (${selectedApplications.size})`,
        html: summaryHtml,
        width: '600px',
        confirmButtonText: 'Sawa'
    });
}

function enableBatchActionButtons(enable) {
    const buttons = ['exportCSVBtn', 'exportExcelBtn', 'batchRejectBtn'];
    
    buttons.forEach(buttonId => {
        const btn = document.getElementById(buttonId);
        if (btn) {
            btn.disabled = !enable;
            if (!enable) {
                btn.title = 'Chagua angalau ombi moja';
            } else {
                btn.title = '';
            }
        }
    });
}

// Enhanced export functions with cross-page support
function exportAndApprove(format) {
    if (selectedApplications.size === 0) {
        showToast('Hakuna maombi yaliyochaguliwa', 'warning');
        return;
    }
    
    let message = `Je, una uhakika unataka ku-export na kuidhinisha maombi ${selectedApplications.size} yaliyochaguliwa kwa format ya ${format.toUpperCase()}?`;
    
    Swal.fire({
        title: `Export ${format.toUpperCase()} + Idhinisha`,
        html: message + '<br><small class="text-gray-600">Maombi yamechaguliwa kutoka kurasa mbalimbali</small>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: format === 'csv' ? '#2563EB' : '#059669',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ndio, Export + Idhinisha',
        cancelButtonText: 'Ghairi'
    }).then((result) => {
        if (result.isConfirmed) {
            const requestData = {
                application_ids: Array.from(selectedApplications),
                export_format: format
            };
            
            const buttonId = format === 'csv' ? 'exportCSVBtn' : 'exportExcelBtn';
            const button = document.getElementById(buttonId);
            const originalText = button.textContent;
            
            button.disabled = true;
            button.textContent = 'Inaexport...';
            
            fetch('{{ route("deductions.batch-approve") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    downloadExportData(data.export_data, format);
                    clearSelection();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showToast(data.message || 'Hitilafu imetokea', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Hitilafu imetokea wakati wa kuwasiliana na seva', 'error');
            })
            .finally(() => {
                button.disabled = false;
                button.textContent = originalText;
            });
        }
    });
}

// Function to download export data
function downloadExportData(exportData, format) {
    let csvContent = '';
    exportData.forEach(row => {
        csvContent += '"' + row.join('","') + '"\n';
    });
    
    const blob = new Blob([csvContent], { 
        type: format === 'csv' ? 'text/csv;charset=utf-8;' : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' 
    });
    
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `maombi_approved_${format}_${new Date().toISOString().slice(0,19).replace(/[:-]/g, '')}.${format === 'csv' ? 'csv' : 'xlsx'}`;
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
}

function showBatchRejectModal() {
    if (selectedApplications.size === 0) {
        showToast('Hakuna maombi yaliyochaguliwa', 'warning');
        return;
    }
    
    // Update modal title to show count
    const modalTitle = document.querySelector('#batchRejectModal h3');
    if (modalTitle) {
        modalTitle.textContent = `Kataa Maombi Yaliyochaguliwa (${selectedApplications.size})`;
    }
    
    document.getElementById('batchRejectModal').classList.remove('hidden');
    document.getElementById('batchRejectionReason').value = '';
}

function closeBatchRejectModal() {
    document.getElementById('batchRejectModal').classList.add('hidden');
}

function submitBatchRejection() {
    const reason = document.getElementById('batchRejectionReason').value.trim();
    
    if (!reason) {
        showToast('Tafadhali andika sababu za kukataa', 'error');
        return;
    }
    
    const submitBtn = document.getElementById('submitBatchRejectBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Inakataa...';
    
    const requestData = {
        application_ids: Array.from(selectedApplications),
        rejection_reason: reason
    };
    
    fetch('{{ route("deductions.batch-reject") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeBatchRejectModal();
            clearSelection();
            setTimeout(() => location.reload(), 2000);
        } else {
            showToast(data.message || 'Hitilafu imetokea', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Hitilafu imetokea wakati wa kuwasiliana na seva', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Kataa Maombi Yote';
    });
}

// Enhanced initialization
document.addEventListener('DOMContentLoaded', function() {
    // Load selections from storage first
    loadSelectionsFromStorage();
    
    // Restore checkbox states based on stored selections
    document.querySelectorAll('.application-checkbox').forEach(checkbox => {
        if (selectedApplications.has(checkbox.value)) {
            checkbox.checked = true;
        }
        
        // Add event listener for individual checkbox changes
        checkbox.addEventListener('change', function() {
            updateBatchActions();
        });
    });
    
    // Store current page applications data
    storeCurrentPageApplications();
    
    // Update batch actions to reflect stored selections
    updateBatchActions();
    
    // Handle modal interactions
    const modal = document.getElementById('rejectModal');
    if (modal) {
        const textarea = modal.querySelector('textarea');
        if (textarea) {
            textarea.addEventListener('input', function() {
                rejectModalData.reason = this.value;
            });
        }
    }
    
    // Clean up old selections (older than 1 hour)
    try {
        const stored = sessionStorage.getItem(STORAGE_KEY);
        if (stored) {
            const storedData = JSON.parse(stored);
            if (storedData.timestamp && (Date.now() - storedData.timestamp) > 3600000) {
                clearSelectionsStorage();
                selectedApplications.clear();
                allApplicationsData.clear();
            }
        }
    } catch (error) {
        console.error('Error cleaning up old selections:', error);
    }
});

function approveApplication(applicationId) {
    Swal.fire({
        title: 'Idhinisha Ombi?',
        text: 'Je, una uhakika unataka kuidhinisha ombi hili?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ndio, Idhinisha',
        cancelButtonText: 'Ghairi'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/deductions/${applicationId}/approve`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function rejectApplication(applicationId) {
    rejectModalData.applicationId = applicationId;
    rejectModalData.reason = '';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    rejectModalData.applicationId = null;
    rejectModalData.reason = '';
}

function submitRejection() {
    if (!rejectModalData.reason.trim()) {
        showToast('Tafadhali andika sababu za kukataa', 'error');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/deductions/${rejectModalData.applicationId}/reject`;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const reasonInput = document.createElement('input');
    reasonInput.type = 'hidden';
    reasonInput.name = 'rejection_reason';
    reasonInput.value = rejectModalData.reason;
    form.appendChild(reasonInput);
    
    document.body.appendChild(form);
    form.submit();
}

// Add cleanup on page unload for memory management
window.addEventListener('beforeunload', function() {
    saveSelectionsToStorage();
});

// Toast notification function
if (typeof showToast === 'undefined') {
    function showToast(message, type = 'info') {
        console.log(`${type.toUpperCase()}: ${message}`);
        
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white max-w-sm ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            type === 'warning' ? 'bg-yellow-500' : 
            'bg-blue-500'
        }`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
}
</script>
@endsection