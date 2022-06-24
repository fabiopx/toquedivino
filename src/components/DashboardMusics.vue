<template>
  <div>
    <v-container class="musicas">
      <!-- Music Card -->
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar dark color="grey darken-4">
              <h3>Músicas</h3>
              <v-spacer></v-spacer>
              <!-- add music -->
              <v-dialog
                v-model="dialogMusic"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-toolbar-items>
                    <v-btn
                      dark
                      text
                      v-bind="attrs"
                      v-on="on"
                      @click="setCrud('c')"
                    >
                      <v-icon>mdi-plus</v-icon> nova música
                    </v-btn>
                  </v-toolbar-items>
                </template>
                <v-card>
                  <v-toolbar dark color="grey darken-4">
                    <v-btn
                      icon
                      dark
                      @click="closeDialog('dialogMusic', 'formMusic')"
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title> Cadastrar Música </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn depressed @click="saveMusic()">
                        <v-icon>mdi-content-save</v-icon> salvar
                      </v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-card-text>
                    <v-form ref="formMusic">
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="musicName"
                            label="Nome da música"
                            :rules="musicNameRules"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-text-field
                            v-model="musicUrl"
                            label="URL da música"
                            :rules="musicUrlRules"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-select
                            v-model="musicHasMoments"
                            label="Momentos"
                            :items="moments"
                            item-text="name"
                            item-value="idmoments"
                            multiple
                            chips
                          ></v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-switch
                            label="Status"
                            v-model="musicStatus"
                          ></v-switch>
                        </v-col>
                      </v-row>
                    </v-form>
                  </v-card-text>
                </v-card>
              </v-dialog>
            </v-toolbar>
            <v-card-text>
              <v-skeleton-loader
                v-if="firstLoad"
                :tableLoading="true"
                type="table"
              >
              </v-skeleton-loader>
              <v-data-table
                :headers="headerMusic"
                :items="musics"
                :items-per-page="5"
                :search="searchMusic"
              >
                <template v-slot:item.url="{ item }">
                  <audio :src="item.url" controls="controls"></audio>
                  <div class="small">{{ item.url }}</div>
                </template>
                <template v-slot:item.moments="{ item }">
                  <v-chip
                    v-for="(m, i) in item.moments"
                    :key="i"
                    class="mr-1 mb-1"
                  >
                    {{ m.name }}</v-chip
                  >
                </template>
                <template v-slot:item.status="{ item }">
                  <v-chip color="primary" v-if="item.status">Ativa</v-chip>
                  <v-chip v-else>Inativa</v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-btn color="primary" icon @click="editMusic(item)">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn color="red" icon @click="deleteMusic(item.idmusic)">
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <!-- Fim Music Card -->
    </v-container>
  </div>
</template>

<script>
export default {
  name: "DashboardMusics",

  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      firstLoad: false,
      loadingVisible: false,
      dialogMusic: false,
      musicId: "",
      musicName: "",
      musicNameRules: [(v) => !!v || "O campo NOME DA MÚSICA é requerido"],
      musicUrl: "",
      musicUrlRules: [(v) => !!v || "O campo URL DA MÚSICA é requerido"],
      musicStatus: true,
      searchMusic: "",
      headerMusic: [
        {
          text: "Música",
          value: "name",
        },
        {
          text: "URL",
          value: "url",
        },
        {
          text: "Momentos",
          value: "moments",
        },
        {
          text: "Status",
          value: "status",
        },
        {
          text: "Ações",
          value: "actions",
        },
      ],
      musics: [],
      musicHasMoments: [],
      momentsId: "",
      momentsName: "",
      momentsNameRules: [(v) => !!v || "O campo NOME DO MOMENTO é requerido"],
      momentsDescription: "",
      momentsDescriptionRules: [(v) => !!v || "O campo DESCRIÇÃO é requerido"],
      momentsStatus: true,
      searchMoments: "",
      headerMoments: [
        {
          text: "Momentos",
          value: "name",
        },
        {
          text: "Status",
          value: "status",
        },
        {
          text: "Ações",
          value: "actions",
          sortable: false,
        },
      ],
      moments: [],
    };
  },

  mounted() {
    //
  },

  methods: {
    setCrud: function (op) {
      this.crud = op;
    },

    closeDialog: function (d, form = null) {
      this[d] = false;
      if (form != null) {
        this.$refs[form].reset();
      }
    },

    stopContentLoading: function () {
      this.contentLoading = false;
      this.firstLoad = false;
    },

    clearFormMusic: function () {
      this.$refs.formMusic.reset();
    },

    saveMusic: function () {
      if (this.$refs.formMusic.validate()) {
        this.closeDialog("dialogMusic");
        this.loadingVisible = true;
        if (this.musicStatus) {
          this.musicStatus = 1;
        } else {
          this.musicStatus = 0;
        }
        let data = new FormData();
        data.append("name", this.musicName);
        data.append("url", this.musicUrl);
        data.append("status", this.musicStatus);
        data.append("moments", JSON.stringify(this.musicHasMoments));

        if (this.crud == "c") {
          axios(this.apiURL + "/musics/create", {
            method: "POST",
            data: data,
          }).then((response) => {
            this.loadingVisible = false;
            this.clearFormMusic();
            this.musicName = "";
            this.musicUrl = "";
            this.musicStatus = true;
            this.musics = [];
            this.$swal(response.data.msg, "", response.data.icon);
            this.getMusic();
          });
        } else if (this.crud == "u") {
          axios(this.apiURL + "/musics/update/" + this.musicId, {
            method: "POST",
            data: data,
          }).then((response) => {
            this.loadingVisible = false;
            this.musicId = "";
            this.musicName = "";
            this.musicUrl = "";
            this.musicStatus = true;
            this.musics = [];
            this.$swal(response.data.msg, "", response.data.icon);
            this.getMusic();
            this.clearFormMusic();
          });
        }
      }
    },

    getMusic: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/musics/get");
      this.musics = response.data;
      this.stopContentLoading();
    },

    editMusic: function (item) {
      this.dialogMusic = true;
      this.crud = "u";
      this.musicId = item.idmusic;
      this.musicName = item.name;
      this.musicUrl = item.url;
      this.musicStatus = item.status;
      this.musicHasMoments = item.moments.map((item) => item.idmoments);
    },

    deleteMusic: function (id) {
      this.$swal({
        title: "Deseja deletar a música?",
        icon: "question",
        confirmButtonText: "Deletar",
        cancelButtonText: "Cancelar",
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          axios.get(this.apiURL + "/musics/delete/" + id).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            this.getMusic();
          });
        }
      });
    },

    getMoments: async function () {
      const response = await axios.get(this.apiURL + "/momentos/get");
      this.moments = response.data;
    },
    
  },

  created: async function () {
    await this.getMusic();
    await this.getMoments();
  },
};
</script>

