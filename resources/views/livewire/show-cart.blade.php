<div>
    <!--======= PRODUCT MODAL PART START ========-->
    <div wire:ignore.self
         class="modal fade product-modal"
         id="cartModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
            <div class="modal-content">

                @if (!blank($menuItem))

                    {{-- IMAGE + CLOSE --}}
                    <div class="product-modal-media">
                        <img src="{{ $menuItem->image }}"
     alt="{{ $menuItem->name }}"
     style="width:100%; height:450px; object-fit:cover;">

                        <button type="button"
                                class="fa-regular fa-circle-xmark"
                                data-bs-dismiss="modal"></button>
                    </div>

                    {{-- TITLE + DESC --}}
                    <div class="product-modal-group text-center">
                        <h3 class="product-modal-title" style="background-color: transparent;">
                            {{ $menuItem->name }}
                        </h3>
                        <p class="product-modal-describe">
                            {!! $menuItem->description !!}
                        </p>
                        
<div class="d-flex justify-content-center gap-3" style="margin-top:6px">
                        
                                        <a href="#"
                                           target="_blank"
                                           class="btn btn-dark px-4 py-2">
                                            📱 Android
                                        </a>
                        
                                        <!--<a href="https://www.apple.com/app-store/"-->
                                        <!--   target="_blank"-->
                                        <!--   class="btn btn-dark px-4 py-2">-->
                                        <!--    🍎 iOS-->
                                        <!--</a>-->
                        
                                    </div>

                    </div>

                    {{-- FORM (kept for Livewire safety) --}}
                                            <form wire:submit.prevent>
                        
                          
                        
                            {{-- FOOTER --}}
                            <!--<div class="product-modal-footer flex-column">-->
                        
                            <!--    {{-- APP DOWNLOAD CTA --}}-->
                            <!--    <div class="app-only-box text-center w-100">-->
                        
                            <!--        <h5 class="mb-1 fw-bold">-->
                            <!--            📱 Order on Our App-->
                            <!--        </h5>-->
                        
                            <!--        <small class="text-muted d-block mb-3">-->
                            <!--            Download our app for a faster and smoother ordering experience.-->
                            <!--        </small>-->
                        
                            <!--        <div class="d-flex justify-content-center gap-3">-->
                        
                            <!--            <a href="#"-->
                            <!--               target="_blank"-->
                            <!--               class="btn btn-dark px-4 py-2">-->
                            <!--                📱 Android-->
                            <!--            </a>-->
                        
                                        <!--<a href="https://www.apple.com/app-store/"-->
                                        <!--   target="_blank"-->
                                        <!--   class="btn btn-dark px-4 py-2">-->
                                        <!--    🍎 iOS-->
                                        <!--</a>-->
                        
                            <!--        </div>-->
                            <!--    </div>-->
                        
                            <!--</div>-->
                        </form>

                @endif

            </div>
        </div>
    </div>
    <!--======= PRODUCT MODAL PART END =====-->
</div>
