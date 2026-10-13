<x-customers-layout>
    <div class="space-y-6">
        
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">نظام المشتركون والعملاء</h2>
                <p class="text-gray-500 mt-1 text-sm">مرحباً بك في إدارة المشتركين والعملاء</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <a href="{{ route('customers.index') }}" class="group bg-white rounded-3xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 hover:shadow-lg hover:border-orange/30 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-orange/10 text-orange flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <x-heroicon-o-users class="w-6 h-6" />
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">إدارة المشتركين</h3>
                <p class="text-sm text-gray-500">استعراض وإدارة بيانات المشتركين والعملاء</p>
            </a>
        </div>
        
    </div>
</x-customers-layout>
