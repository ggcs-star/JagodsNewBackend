<aside id="profileSidebar" class="ltr:translate-x-[105%] rtl:-translate-x-[105%] fixed top-0 ltr:right-0 rtl:left-0 z-[60] w-full h-full max-w-sm ltr:shadow-sidebar-left rtl:shadow-sidebar-right transition-all duration-500 bg-white">
    <div class="w-fit mx-auto text-center py-5">
        <button class="fa-solid fa-xmark absolute top-4 ltr:right-4 rtl:left-4 text-white bg-[#FB4E4E] xmark-btn"></button>
        <figure class="relative z-10 w-[98px] h-[98px] border-2 border-dashed rounded-full inline-flex items-center justify-center border-white bg-gradient-to-t from-[#FF7A00] to-[#FF016C] 
        before:absolute before:top-1/2 before:left-1/2 before:-translate-x-1/2 before:-translate-y-1/2 before:w-24 before:h-24 before:rounded-full before:-z-10 before:bg-white">
            <img class="w-[90px] h-[90px] rounded-full shadow-avatar" src="{{ auth()->user()->image }}" alt="avatar">
        </figure>
        
        <h3 class="font-medium text-sm leading-6 capitalize mb-0.5">{{ auth()->user()->name }}</h3>
        <p class="text-xs mb-0.5">{{ auth()->user()?->email ?? "" }}</p>
        <p class="text-xs">{{ auth()->user()?->phone ?? "" }}</p>
    </div>
    <nav class="px-4 h-[calc(100vh_-_225px)] overflow-y-auto thin-scrolling">
        <a href="{{ route('admin.profile') }}" class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 10.9V4.1C22 2.6 21.36 2 19.77 2H15.73C14.14 2 13.5 2.6 13.5 4.1V10.9C13.5 12.4 14.14 13 15.73 13H19.77C21.36 13 22 12.4 22 10.9Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M22 19.9V18.1C22 16.6 21.36 16 19.77 16H15.73C14.14 16 13.5 16.6 13.5 18.1V19.9C13.5 21.4 14.14 22 15.73 22H19.77C21.36 22 22 21.4 22 19.9Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.5 13.1V19.9C10.5 21.4 9.86 22 8.27 22H4.23C2.64 22 2 21.4 2 19.9V13.1C2 11.6 2.64 11 4.23 11H8.27C9.86 11 10.5 11.6 10.5 13.1Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.5 4.1V5.9C10.5 7.4 9.86 8 8.27 8H4.23C2.64 8 2 7.4 2 5.9V4.1C2 2.6 2.64 2 4.23 2H8.27C9.86 2 10.5 2.6 10.5 4.1Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="text-sm leading-6 capitalize">{{ __('Profile') }}</span>
        </a>
       
        <a href="{{ route('admin.profile.edit') }}" class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.33333 1.33301H5.99999C2.66666 1.33301 1.33333 2.66634 1.33333 5.99967V9.99967C1.33333 13.333 2.66666 14.6663 5.99999 14.6663H9.99999C13.3333 14.6663 14.6667 13.333 14.6667 9.99967V8.66634" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.6933 2.0135L5.44 7.26684C5.24 7.46684 5.04 7.86017 5 8.14684L4.71333 10.1535C4.60666 10.8802 5.12 11.3868 5.84666 11.2868L7.85333 11.0002C8.13333 10.9602 8.52666 10.7602 8.73333 10.5602L13.9867 5.30684C14.8933 4.40017 15.32 3.34684 13.9867 2.0135C12.6533 0.680168 11.6 1.10684 10.6933 2.0135Z" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9.94 2.7666C10.3867 4.35993 11.6333 5.6066 13.2333 6.05993" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            </svg> 
            <span class="text-sm leading-6 capitalize">{{ __('levels.edit_profile') }}</span>
        </a>
       
        @if (!blank(auth()->user()->bank))
        <a href="{{ route('admin.profile.bank-edit') }}" class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" enable-background="new 0 0 67 67" viewBox="0 0 67 67">
                <path d="M33.9,16.1c-0.7,0-1.2-0.6-1.2-1.2c0-0.6-0.4-1-1-1s-1,0.4-1,1c0,1.4,0.9,2.6,2.1,3v0.8c0,0.6,0.4,1,1,1s1-0.4,1-1V18
                      c1.3-0.4,2.3-1.6,2.3-3.1c0-1.8-1.4-3.2-3.2-3.2c-0.7,0-1.2-0.6-1.2-1.2c0-0.7,0.5-1.2,1.2-1.2c0,0,0,0,0,0s0,0,0,0
                      c0.7,0,1.2,0.6,1.2,1.2c0,0.6,0.4,1,1,1s1-0.4,1-1c0-1.4-0.9-2.6-2.2-3.1V6.6c0-0.6-0.4-1-1-1s-1,0.4-1,1v0.8
                      c-1.3,0.4-2.2,1.6-2.2,3.1c0,1.8,1.4,3.2,3.2,3.2c0.7,0,1.2,0.6,1.2,1.2S34.6,16.1,33.9,16.1z"></path>
                <path d="M65.9,16.6l-32-16c-0.3-0.1-0.6-0.1-0.9,0l-32,16c-0.3,0.2-0.6,0.5-0.6,0.9v6.1c0,0.6,0.4,1,1,1h0.9v3.1c0,0.6,0.4,1,1,1
                      h0.3v3.1c0,0.5,0.4,0.9,0.8,1v17.8c-0.5,0.1-0.8,0.5-0.8,1v3.1H3.4c-0.6,0-1,0.4-1,1v3.1H1.5c-0.6,0-1,0.4-1,1v6c0,0.6,0.4,1,1,1
                      h64c0.6,0,1-0.4,1-1v-6c0-0.6-0.4-1-1-1h-0.9v-3.1c0-0.6-0.4-1-1-1h-0.3v-3.1c0-0.5-0.4-0.9-0.8-1V32.6c0.5-0.1,0.8-0.5,0.8-1v-3.1
                      h0.3c0.6,0,1-0.4,1-1v-3.1h0.9c0.6,0,1-0.4,1-1v-6.1C66.5,17.1,66.3,16.8,65.9,16.6z M2.5,18.1l31-15.5l31,15.5v4.5
                      c-20.1,0-43.8,0-62,0V18.1z M60.5,50.4h-3.9V32.7h3.9V50.4z M53.5,28.6h0.3v3.1c0,0.5,0.4,0.9,0.8,1v17.8c-0.5,0.1-0.8,0.5-0.8,1
                      v3.1h-0.3c-0.6,0-1,0.4-1,1v3.1h-4.6v-3.1c0-0.6-0.4-1-1-1h-0.3v-3.1c0-0.5-0.4-0.9-0.8-1V32.6c0.5-0.1,0.8-0.5,0.8-1v-3.1h0.3
                      c0.6,0,1-0.4,1-1v-3.1h4.6v3.1C52.5,28.2,52.9,28.6,53.5,28.6z M31.2,58.5v-3.1c0-0.6-0.4-1-1-1h-0.3v-3.1c0-0.5-0.4-0.9-0.8-1
                      V32.6c0.5-0.1,0.8-0.5,0.8-1v-3.1h0.3c0.6,0,1-0.4,1-1v-3.1h4.6v3.1c0,0.6,0.4,1,1,1h0.3v3.1c0,0.5,0.4,0.9,0.8,1v17.8
                      c-0.5,0.1-0.8,0.5-0.8,1v3.1h-0.3c-0.6,0-1,0.4-1,1v3.1H31.2z M14.5,58.5v-3.1c0-0.6-0.4-1-1-1h-0.3v-3.1c0-0.5-0.4-0.9-0.8-1V32.6
                      c0.5-0.1,0.8-0.5,0.8-1v-3.1h0.3c0.6,0,1-0.4,1-1v-3.1h4.6v3.1c0,0.6,0.4,1,1,1h0.3v3.1c0,0.5,0.4,0.9,0.8,1v17.8
                      c-0.5,0.1-0.8,0.5-0.8,1v3.1h-0.3c-0.6,0-1,0.4-1,1v3.1H14.5z M6.5,32.7h3.9v17.7H6.5V32.7z M27.1,50.4h-3.9V32.7h3.9V50.4z
                       M22.4,52.4h5.6v2.1h-5.6V52.4z M27.9,30.7h-5.6v-2.1h5.6V30.7z M29.2,26.6c-0.2,0-8.5,0-8.1,0v-2.1h8.1V26.6z M21.1,56.4
                      c2.1,0,6.2,0,8.1,0v2.1h-8.1V56.4z M43.8,50.4h-3.9V32.7h3.9V50.4z M39.1,52.4h5.6v2.1h-5.6V52.4z M44.6,30.7h-5.6v-2.1h5.6V30.7z
                       M45.9,26.6c-2.1,0-6.2,0-8.1,0v-2.1h8.1V26.6z M37.8,56.4c2.1,0,6.2,0,8.1,0v2.1h-8.1V56.4z M12.5,24.6v2.1c-2.1,0-6.2,0-8.1,0
                      v-2.1H12.5z M11.2,28.6v2.1H5.7v-2.1H11.2z M5.7,52.4h5.6v2.1H5.7V52.4z M4.4,56.4c2.1,0,6.2,0,8.1,0v2.1H4.4V56.4z M64.5,64.5h-62
                      v-4c14.6,0,47.4,0,62,0V64.5z M54.5,58.5v-2.1c0.2,0,8.5,0,8.1,0v2.1H54.5z M55.8,54.4v-2.1h5.6v2.1H55.8z M61.3,30.7h-5.6v-2.1
                      h5.6V30.7z M62.6,26.6c-0.2,0-8.5,0-8.1,0v-2.1h8.1V26.6z"></path>
              </svg>
            <span class="text-sm leading-6 capitalize">{{ __('levels.bank') }}</span>
        </a>
        @endif
        
        {{-- need to edit it by login as restaurant owner --}}
        @if(auth()->user()->myrole == 3 && auth()->user()->restaurant)
        <a href="{{ route('admin.restaurant.restaurant-edit',auth()->user()->restaurant) }}" class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
            <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="#1e1f27" d="M288.5 366.847h133a15.517 15.517 0 0 0 15.5-15.5V270.5a15.517 15.517 0 0 0-15.5-15.5h-133a15.517 15.517 0 0 0-15.5 15.5v80.847a15.517 15.517 0 0 0 15.5 15.5Zm74-96.847h59a.5.5 0 0 1 .5.5v32.923h-59.5Zm0 48.423H422v32.924a.5.5 0 0 1-.5.5h-59ZM288 270.5a.5.5 0 0 1 .5-.5h59v33.423H288Zm0 47.923h59.5v33.424h-59a.5.5 0 0 1-.5-.5ZM499.5 216a12.514 12.514 0 0 0 12.5-12.5v-20a12.514 12.514 0 0 0-12.5-12.5h-116v-30h12a17.52 17.52 0 0 0 17.5-17.5v-106A17.52 17.52 0 0 0 395.5 0h-279A17.52 17.52 0 0 0 99 17.5v106a17.52 17.52 0 0 0 17.5 17.5h12v30h-116A12.514 12.514 0 0 0 0 183.5v20A12.514 12.514 0 0 0 12.5 216H30v251H12.5A12.514 12.514 0 0 0 0 479.5v20A12.514 12.514 0 0 0 12.5 512h487a12.514 12.514 0 0 0 12.5-12.5v-20a12.514 12.514 0 0 0-12.5-12.5H482V216Zm-383-90a2.503 2.503 0 0 1-2.5-2.5v-106a2.503 2.503 0 0 1 2.5-2.5h279a2.503 2.503 0 0 1 2.5 2.5v106a2.503 2.503 0 0 1-2.5 2.5Zm252 15v30h-225v-30ZM497 482v15H15v-15ZM225.5 255h-119A17.52 17.52 0 0 0 89 272.5V467H45V216h422v251H243V272.5a17.52 17.52 0 0 0-17.5-17.5Zm-35 60v17h-49v-17h3.83l.036.002.028-.002Zm-33.44-15 9.313-20.316L176.776 300Zm-16.5 0H134a7.5 7.5 0 0 0-7.5 7.5v32a7.5 7.5 0 0 0 7.5 7.5h64a7.5 7.5 0 0 0 7.5-7.5v-32a7.5 7.5 0 0 0-7.5-7.5h-4.371l-15.362-30H225.5a2.503 2.503 0 0 1 2.5 2.5V467H104V272.5a2.503 2.503 0 0 1 2.5-2.5h47.812ZM15 201v-15h482v15ZM320.937 96h-7.5v-5.43a49.556 49.556 0 0 0-49.5-49.5h-.437V37.5a7.5 7.5 0 0 0-15 0v3.57h-.437a49.556 49.556 0 0 0-49.5 49.5V96h-7.5a7.5 7.5 0 0 0 0 15h129.873a7.5 7.5 0 0 0 0-15Zm-22.5 0h-84.873v-5.43a34.539 34.539 0 0 1 34.5-34.5h15.873a34.539 34.539 0 0 1 34.5 34.5Z"></path>
              </svg>
            <span class="text-sm leading-6 capitalize">{{ __('validation.attributes.restaurant_id') }}</span>
        </a>
        @endif
        
        <a href="{{ route('admin.profile.address-index') }}" class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.6667 5.99968V9.99968C14.6667 11.6664 14.3333 12.833 13.5867 13.5864L9.33333 9.33302L14.4867 4.17969C14.6067 4.70635 14.6667 5.30635 14.6667 5.99968Z" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14.4867 4.17968L4.17999 14.4863C2.17332 14.0263 1.33333 12.6397 1.33333 9.99967V5.99967C1.33333 2.66634 2.66666 1.33301 5.99999 1.33301H9.99999C12.64 1.33301 14.0267 2.17301 14.4867 4.17968Z" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.5867 13.5863C12.8333 14.333 11.6667 14.6663 10 14.6663H6C5.30667 14.6663 4.70666 14.6063 4.17999 14.4863L9.33333 9.33301L13.5867 13.5863Z" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4.15999 5.32047C4.61333 3.36714 7.54667 3.36714 8 5.32047C8.26 6.46714 7.53999 7.44047 6.90666 8.04047C6.44666 8.48047 5.72 8.48047 5.25334 8.04047C4.62 7.44047 3.89333 6.46714 4.15999 5.32047Z"/>
                <path d="M6.06307 5.80013H6.06906" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="text-sm leading-6 capitalize">{{ __('levels.address') }}</span>
        </a>
        <a href="{{ route('admin.profile.password-edit') }}" class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.1933 9.95289C11.82 11.3196 9.85333 11.7396 8.12666 11.1996L4.98666 14.3329C4.76 14.5662 4.31333 14.7062 3.99333 14.6596L2.54 14.4596C2.06 14.3929 1.61333 13.9396 1.54 13.4596L1.34 12.0062C1.29333 11.6862 1.44666 11.2396 1.66666 11.0129L4.8 7.87956C4.26666 6.14622 4.68 4.17956 6.05333 2.81289C8.02 0.846224 11.2133 0.846224 13.1867 2.81289C15.16 4.77956 15.16 7.98622 13.1933 9.95289Z" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4.59334 11.6602L6.12667 13.1935" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9.66667 7.33301C10.219 7.33301 10.6667 6.88529 10.6667 6.33301C10.6667 5.78072 10.219 5.33301 9.66667 5.33301C9.11439 5.33301 8.66667 5.78072 8.66667 6.33301C8.66667 6.88529 9.11439 7.33301 9.66667 7.33301Z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="text-sm leading-6 capitalize">{{ __('levels.change_password') }}</span>
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.93333 5.04016C6.14 2.64016 7.37333 1.66016 10.0733 1.66016H10.16C13.14 1.66016 14.3333 2.85349 14.3333 5.83349V10.1802C14.3333 13.1602 13.14 14.3535 10.16 14.3535H10.0733C7.39333 14.3535 6.16 13.3868 5.94 11.0268" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10 8H2.41333" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3.90001 5.7666L1.66667 7.99994L3.90001 10.2333" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="text-sm leading-6 capitalize">{{ __('Logout') }}</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </nav>
</aside>