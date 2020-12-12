<template>
    <div>
        <h1>I m com A and this counter is : {{ counter }}</h1>
    </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
    computed : {
        ...mapGetters({
            'counter' : 'getCounter'
        })
    },
}
</script>