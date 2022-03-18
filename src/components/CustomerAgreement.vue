<template>
  <div>
    <v-container>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark> Orçamento </v-toolbar>
            <v-card-text>
              <v-banner single-line>
                <v-icon>mdi-account-music</v-icon>
                Formação: {{ formation }}
                <template v-slot:actions>
                  <v-btn depressed>Confirmar</v-btn>
                  <v-btn depressed>Editar</v-btn>
                </template>
              </v-banner>
              <v-banner single-line>
                <v-icon>mdi-music-circle</v-icon>
                Serviço: {{ service }}
                <template v-slot:actions>
                  <v-btn depressed>Confirmar</v-btn>
                  <v-btn depressed>Editar</v-btn>
                </template>
              </v-banner>
              <v-banner two-line>
                <v-icon>mdi-clock-time-three</v-icon>
                Evento:
              </v-banner>
              <v-banner>
                <b>Total:</b>
              </v-banner>
              <v-divider></v-divider>
              <v-btn color="grey darken-4" class="mt-3 pa-4" dark>
                <v-icon class="mr-2">mdi-content-save</v-icon>Salvar orçamento
              </v-btn>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  data: () => ({
    formation: "",
    service: "",
  }),
  methods: {
    //
  },
};
</script>