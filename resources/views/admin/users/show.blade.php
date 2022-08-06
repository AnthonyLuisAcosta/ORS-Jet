<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Users') }} | <span class=" text-md text-gray-500">{{__('View')}}</span>
    </h2>
  </x-slot>
  <div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="block mb-8">
        <a href="{{ route('admin.users.index') }}" class="ml-1 inline-flex items-center px-4 py-1 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-200 focus:shadow-outline-gray hover:text-white disabled:opacity-25 transition ease-in-out duration-150">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
          </svg>
          <span class="flex-1 ml-1 whitespace-nowrap">Back to list</span>
        </a>
      </div>
      <div class="pl-32 max-w-7xl mt-5 sm:col-span-2">
        <div class=" shadow overflow-hidden rounded-lg">
          <div class="px-4 py-5 bg-white sm:p-6 border-0">
            <!--GRID-->
            <div class="grid grid-rows-flow gap-2">
              <!--First Name-->
              <div class="flex">
                <p class="text-md p-2 pl-2 w-1/3 text-right border-r-2 mr-2">Name</p>
                <p class="text-md p-2 w-2/3 block rounded-md bg-gray-100">{{$user->first_name.' '.$user->last_name}}</p>
              </div>
              <div class=" mt-3 flex">
                <p class="text-md p-2 pl-2 w-1/3 text-right border-r-2 mr-2">Email</p>
                <p class="text-md p-2 w-2/3 block rounded-md bg-gray-100">{{$user->email}}</p>
              </div>
              <div class=" mt-3 flex">
                <p class="text-md p-2 pl-2 w-1/3 text-right border-r-2 mr-2">Account Type</p>
                @foreach($roles as $role)
                @if($user->role_id == $role->role_id)
                <p class="text-md p-2 w-2/3 block rounded-md bg-gray-100">{{$role->role_name}}</p>
                @endif
                @endforeach
              </div>
            </div>
          </div>
          <!--END OF GRID-->
          <!--Button-->
        </div>
      </div>
    </div>
  </div>

</x-app-layout>
