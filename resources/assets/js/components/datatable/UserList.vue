<template>
    <div class="text-left">
        <span v-if="rowData.is_admin == 1">
             {{$t('lang.admin')}}
        </span>
        <span v-else>
            {{rowData.role_title}}
        </span>
    </div>
</template>

<script>
    export default {
        props: ['rowData'],
    }
</script>
