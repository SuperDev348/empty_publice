<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0" v-if="id">{{ $t('lang.edit') }}</h4>
                        <h4 class="m-0" v-else>{{ $t('lang.add_custom_field') }}</h4>
                    </div>
                    <div class="col-2 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="">
                            <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <pre-loader v-if="!hidePreLoader"></pre-loader>
        <div class="modal-layout-content" v-else>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="input-label">{{$t('lang.label_name') }}</label>
                    <input id="input-label" name="label"
                           v-model="label" type="text" class="form-control"
                           v-validate="'required'"
                           :class="{ 'is-invalid': submitted && errors.has('label') }">
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('label')">
                            {{
                            errors.first('label') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="field-type">{{$t('lang.field_type') }}</label>
                    <select v-model="fieldType" name="fieldType" id="field-type" class="custom-select"
                            :disabled="isDisable" v-validate="'required'" :data-vv-as="$t('lang.field_type_small')"
                            :class="{ 'is-invalid': submitted && errors.has('fieldType') }">
                        <option value="" disabled selected>{{ $t('lang.choose_one') }}</option>
                        <option value="text">{{ $t('lang.text') }}</option>
                        <option value="textarea">{{ $t('lang.textarea') }}</option>
                        <option value="email">{{ $t('lang.input_email') }}</option>
                        <option value="date">{{ $t('lang.input_date') }}</option>
                        <option value="checkbox">{{ $t('lang.checkbox') }}</option>
                        <option value="number">{{ $t('lang.number') }}</option>
                        <option value="radio">{{ $t('lang.radio') }}</option>
                        <option value="select">{{ $t('lang.select') }}</option>
                    </select>
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('fieldType')">
                            {{
                            errors.first('fieldType') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-md-12"
                     v-if="fieldType === 'checkbox' || fieldType === 'radio' || fieldType ==='select'">
                    <div v-show="options.length>0">
                        <label>{{ $t('lang.options') }}</label>
                        <br>
                        <div class="custom-chip custom-chip-top-margin" v-for="option in options">
                            {{option}} <i class="la la-times" @click="deleteChip(option)"></i>
                        </div>
                    </div>
                    <div class="input-group custom-field-input-group">
                        <input type="text"
                               class="form-control"
                               id="input-options"
                               name="inputOptions"
                               v-model="inputOptions"
                               @keyup.enter="addChips">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" @click="addChips">
                                <i class="las la-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="1" v-model="enableField"
                               name="enableField" checked value="1">
                        <label class="custom-control-label" for="1">{{ $t('lang.enable_field') }}</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="2" v-model="showTable" name="showTable"
                               checked value="1">
                        <label class="custom-control-label" for="2">{{ $t('lang.show_in_table') }}</label>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn app-color mobile-btn" type="submit" @click.prevent="save()">{{$t('lang.save')
                        }}
                    </button>
                    <button class="btn cancel-btn mobile-btn" data-dismiss="modal" @click.prevent="">{{
                        $t('lang.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        props: ['id', 'modalID'],
        extends: axiosGetPost,
        data() {
            return {
                hidePreLoader: true,
                label: '',
                fieldType: '',
                enableField: true,
                showTable: true,
                options: [],
                newOptions: [],
                inputOptions: '',
                table_name: 'service',
                isDisable: false,
                submitted: false,

            }
        },
        created() {
            if (this.id) {
                this.getCustomFiledEdit('/customfiledservice-edit/' + this.id);
            }

        },
        methods: {
            addChips() {
                if (this.inputOptions !== '') {
                    this.options.push(this.inputOptions);
                }
                this.inputOptions = '';
            },
            deleteChip(chip) {
                this.options = this.options.filter(function (element) {
                    return element !== chip;
                });
            },
            save() {
                let instance = this;
                (instance.submitted = true),
                    instance.$validator.validateAll().then(result => {
                        if (result) {
                            instance.inputFields = {
                                label: instance.label,
                                fieldType: instance.fieldType,
                                enableField: instance.enableField,
                                showTable: instance.showTable,
                                tableName: instance.table_name,
                                options: instance.options,
                            };

                            if (instance.id) {
                                instance.postDataMethod('/customfiled-update/' + instance.id, instance.inputFields);
                            } else {
                                instance.postDataMethod('/save-custom-field', instance.inputFields);
                            }
                        }
                    });
            },
            postDataThenFunctionality(response) {
                let instance = this;
                $(instance.modalID).modal('hide');
                instance.$hub.$emit('reloadDataTable');
            },
            postDataCatchFunctionality(error) {
                let instance = this;
            },
            getCustomFiledEdit(route) {
                let instance = this;
                this.setPreLoader(false);
                instance.axiosGet(route,
                    function (response) {
                        instance.label = response.data.label;
                        instance.fieldType = response.data.field_type;
                        instance.enableField = response.data.is_enable;
                        instance.showTable = response.data.show_in_table;
                        instance.options = response.data.options;
                        instance.newOptions = response.data.options;
                        instance.isDisable = true;
                        instance.setPreLoader(true);
                    },
                    function (error) {
                        instance.setPreLoader(true);
                    },
                );
            },
        }
    }
</script>