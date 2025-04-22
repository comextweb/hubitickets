   <nav
       class="{{ $customThemeBackground == 'on' ? 'dash-sidebar light-sidebar transprent-bg' : 'dash-sidebar light-sidebar' }}">

       <div class="navbar-wrapper">
           <div class="m-header main-logo">
               <a href="{{ route('admin.dashboard') }}" class="b-brand">
                   <img src="{{ getFile(getSidebarLogo()) }}{{ '?' . time() }}"
                       alt="{{ config('app.name', 'TicketGo SaaS') }}" class="logo logo-lg">
               </a>
           </div>
           <div class="navbar-content">
               <ul class="dash-navbar">
                   {!! getMenu() !!}
               </ul>
           </div>
       </div>
   </nav>
