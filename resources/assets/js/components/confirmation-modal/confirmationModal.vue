<template>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered confirm-delete-modal" role="document">
            <div class="modal-content">
                <button type="button" class="close position-absolute absolute-right p-3" data-dismiss="modal"
                        aria-label="Close" @click.prevent="">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
                <div class="d-table h-100 w-100">
                    <div class="d-table-cell align-middle text-center">
                        <h5>{{ $t('lang.are_you_sure') }}</h5>
                        <h6>{{ $t('lang.' + message) }}</h6>
                        <div class="mt-3">
                            <button class="btn btn-danger mobile-btn" type="submit" @click.prevent="buttonAction()">
                                {{ $t('lang.'+firstButtonName) }}
                            </button>
                            <button class="btn btn-secondary cancel-btn mobile-btn" data-dismiss="modal"
                                    @click.prevent="">
                                {{ $t('lang.'+secondButtonName) }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['message', 'firstButtonName', 'secondButtonName'],
        methods: {
            buttonAction() {
                this.$emit('confirmationModalButtonAction');
            }
        }
    }
</script>
