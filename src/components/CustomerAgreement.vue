<template>
    <div>
        <v-container>
            <v-row>
                <v-col>
                    <v-card>
                        <v-toolbar color="grey darken-4" dark>
                            <h2><v-icon class="mr-3">mdi-file-sign</v-icon> Contrato</h2>
                        </v-toolbar>
                        <v-card-text>
                            Contrato
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
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