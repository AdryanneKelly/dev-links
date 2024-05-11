<div class="h-screen w-full bg-gradient-to-r from-purple-700 via-blue-500 to-cyan-500">
    <div class="flex lg:flex-row h-[95%] flex-col">
        <div class="flex flex-col justify-center gap-5 h-full lg:w-1/2 w-full px-[10%] text-white">
            <h2
                class="text-3xl text-start font-bold bg-gradient-to-r from-purple-500 via-blue-400 to-cyan-300 text-transparent max-w-fit bg-clip-text">
                DevLinks
            </h2>
            <div class="h-1 bg-gradient-to-r from-purple-500 via-blue-400 to-cyan-300 rounded-full max-w-44">
            </div>
            <p class="text-4xl max-w-[400px] font-bold  uppercase">Usuário não encontrado em nossa base</p>
            <a href="{{ route('filament.admin.pages.dashboard') }}" class="text-white font-bold text-xl max-w-fit">
                <div
                    class="border-4 border-white rounded-full hover:bg-white hover:bg-opacity-20 px-4 py-2 max-w-80 text-center">
                    Voltar para a página inicial
                </div>
            </a>
        </div>
        <div
            class="flex flex-col justify-center h-full items-center lg:h-full lg:w-1/2 w-1/2 max-sm:w-full sm:w-full sm:h-1/2 bg-slate-200 ">
            <img src="{{ asset('/images/not-found.svg') }}" alt="" class="max-w-[850px] rounded-full">
        </div>
    </div>
    <div class="h-[5%] flex flex-col justify-center text-center items-center bg-purple-700">
        <p class="text-white font-bold px-2">Copyright &copy; DevLinks 2024 - Developed by Ackalantys Dev</p>
    </div>
</div>
