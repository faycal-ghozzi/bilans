<div class="bg-btlGreen w-64 h-screen fixed top-[4rem]">
    <nav class="py-6">
        <ul class="space-y-4">
            <li>
                <a href="{{ route('financial-statement.show', ['id' => $id]) }}" class="flex items-center px-4 py-4 hover:bg-btlRed hover:text-white {{ Route::is('financial-statement.show') ? 'text-btlGreen bg-white' : 'text-white'}}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <!-- Document Icon -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v7a2 2 0 01-2 2h-2" />
                        <!-- Magnifying Glass -->
                        <circle cx="15" cy="16" r="3" />
                        <line x1="18" y1="19" x2="21" y2="22" stroke="currentColor" stroke-width="2" />
                    </svg>
                    Consulter
                </a>
            </li>
            <li>
                <a href="{{ route('financial.analysis', ['id' => $id]) }}" class="flex items-center px-4 py-4 hover:bg-btlRed hover:text-white {{ Route::is('financial.analysis') ? 'text-btlGreen bg-white' : 'text-white'}}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <!-- Ratios Icon -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h2v10H3V10zm6-4h2v14H9V6zm6 8h2v6h-2v-6zm6-8h2v14h-2V6z" />
                    </svg>
                    Ratios
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center px-4 py-4 hover:bg-btlRed hover:text-white {{ request()->is('download-path*') ? 'text-btlGreen bg-white' : 'text-white'}}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <!-- Download Icon -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4" />
                        <rect x="4" y="18" width="16" height="2" rx="1" />
                    </svg>
                    Télecharger
                </a>
            </li>
        </ul>
    </nav>
</div>
