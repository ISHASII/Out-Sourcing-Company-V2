@extends('layouts.app')

@section('title', 'PT. Unggul Cipta Indah | Professional Outsourcing')

@section('content')
    <div class="min-h-screen flex flex-col font-sans text-slate-800 bg-slate-50">
                <x-landing.header />

        <main class="flex-grow">
                    <x-landing.hero />

                    <x-landing.stats />

                    <x-landing.about />

                    <x-landing.vision-mission />

                    <x-landing.services />
                    <x-landing.partners />

                    <x-landing.workflow />

                    <x-landing.jobs :postings="$postings" />

                    <x-landing.cta />
        </main>

                <x-landing.footer />
                
        <!-- WhatsApp Float Overlay -->
        <x-whatsapp-float />
    </div>
@endsection