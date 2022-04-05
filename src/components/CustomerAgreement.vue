<template>
    <div>
        <v-container>
            Agreement
        </v-container>
    </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";

export default {
    data: () => ({
        apiURL: process.env.VUE_APP_URL,
    }),
    methods: {
        ...mapActions(["setInscribeID", "setUserNow"]),
    },
    computed: {
        ...mapGetters(["inscribeID", "userNow", "isAgreement", "isBudget"]),
    }
}
</script>