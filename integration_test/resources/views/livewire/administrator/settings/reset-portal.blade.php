<div class="h-100">
    <style>
        .boxShadow {
            margin: 10px auto;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>

    <h3 style="padding-top: 10px;padding-left: 10px;">
        Reset Portal:
    </h3>
    <div class="row">
        <div class="col-lg-12 col-md-12 col" style="margin-left: auto;margin-right:auto">

            <div class="container boxShadow py-5">
                <div class="row">
                    <div class="col-md-6 mx-auto text-center">
                        <h2>Are you sure to reset the portal! this is not revertable.</h2>
                        <button
                            class="btn btn-danger btn-lg"
                            type="button"
                            wire:click="resetPortal"
                            wire:confirm.prompt="Are you sure?\n\nType DELETE to confirm|DELETE">
                            <span wire:loading>
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Resetting...</span>
                                </div>
                                Resetting...
                            </span>
                            <span wire:loading.remove>Full Reset</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>