<template>
  <div>
    <v-container fluid>
      <v-row>
        <v-col>
          <p class="text-h4 white--text">
            <v-icon color="white">mdi-music-circle</v-icon> Repertório
          </p>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark>
              <v-toolbar-title>Gerenciar repertório</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-toolbar-items>
                <v-btn
                  text
                  v-show="!startedRepertory"
                  @click="startRepertory()"
                >
                  <v-icon>mdi-play</v-icon> iniciar
                </v-btn>
              </v-toolbar-items>
            </v-toolbar>
            <v-card-text v-show="!startedRepertory">
              <v-container>
                <v-row>
                  <v-col></v-col>
                  <v-col class="align-center">
                    <v-img
                      src="../assets/undraw_media_player_ylg8.svg"
                      max-width="600"
                    >
                    </v-img>
                    <span class="text-h4 ma-4"
                      >Ainda não há repertório inicializado</span
                    >
                  </v-col>
                  <v-col></v-col>
                </v-row>
              </v-container>
            </v-card-text>
            <v-card-text v-show="startedRepertory">
              <v-container>
                <v-row>
                  <v-col>
                    <v-form ref="formAddRepertoryItem">
                      <v-skeleton-loader
                        v-if="loadingSelectFields"
                        type="text@2, button"
                      >
                      </v-skeleton-loader>
                      <v-autocomplete
                        v-model="repertoryMusic"
                        :loading="loading"
                        :items="music"
                        item-text="name"
                        flat
                        label="Procure por uma música"
                        return-object
                      >
                      </v-autocomplete>
                      <!-- <v-select
                        v-show="!loadingSelectFields"
                        v-model="repertoryMoments"
                        label="Momento"
                        :items="moments"
                        item-text="name"
                        item-value="idmoments"
                        @change="getMusic(repertoryMoments)"
                      >
                      </v-select> -->
                      <!-- <v-select
                        v-show="!loadingSelectFields"
                        v-model="repertoryMusic"
                        label="Música"
                        :items="music"
                        item-text="name"
                        item-value="idmusic"
                        return-object
                      ></v-select> -->

                      <audio
                        v-show="repertoryMusic"
                        :src="repertoryMusic.url"
                        controls="controls"
                      ></audio>
                      <v-sheet
                        rounded
                        class="pa-5 grey lighten-3"
                        v-show="repertoryMusic"
                      >
                        <h3>Selecione um momento</h3>
                        <hr class="white mt-1 mb-2" />
                        <v-chip-group v-model="repertoryMoments" column>
                          <v-chip
                            filter
                            class="ma-1"
                            v-for="moments in repertoryMusic.moments"
                            :key="moments.idmoments"
                            :value="moments.idmoments"
                            >{{ moments.name }}</v-chip
                          >
                        </v-chip-group>
                      </v-sheet>
                      <v-btn
                        v-show="!loadingSelectFields"
                        depressed
                        class="grey darken-4 my-3"
                        dark
                        @click="addRepertoryItem()"
                      >
                        <v-icon>mdi-plus</v-icon> Adicionar Música
                      </v-btn>
                    </v-form>
                    <v-alert v-if="alert" color="red" border="left" dark>Selecione uma música e um momento</v-alert>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <v-card elevation="0">
                      <v-card-title>Músicas Escolhidas</v-card-title>
                      <v-card-text>
                        <v-skeleton-loader
                          v-if="loadingListRepertory"
                          type="text@2, button"
                        ></v-skeleton-loader>
                        <v-list v-show="!loadingListRepertory">
                          <v-list-item v-for="(item, i) in repertory" :key="i">
                            <v-card width="100%" class="mb-2">
                              <v-card-title>
                                <span class="font-weight-bold mr-2"
                                  >{{ item.sequence }})</span
                                >
                                <span class="font-weight-bold">
                                  {{ item.moments.name }}</span
                                >: {{ item.music.name }}
                              </v-card-title>
                              <v-card-text>
                                <v-list-item-content>
                                  <v-list-item-title>
                                    <audio
                                      :src="item.music.url"
                                      controls="controls"
                                      width="200"
                                    ></audio>
                                  </v-list-item-title>
                                </v-list-item-content>
                              </v-card-text>
                              <v-card-actions>
                                <v-btn
                                  icon
                                  v-show="item.sequence != 1"
                                  @click="
                                    upRepertoryItemSequence(
                                      item.id,
                                      item.repertory,
                                      item.sequence
                                    )
                                  "
                                  ><v-icon>mdi-arrow-up-circle</v-icon></v-btn
                                >
                                <v-btn
                                  icon
                                  v-show="maxSequence != item.sequence"
                                  @click="
                                    downRepertoryItemSequence(
                                      item.id,
                                      item.repertory,
                                      item.sequence
                                    )
                                  "
                                  ><v-icon>mdi-arrow-down-circle</v-icon></v-btn
                                >
                                <v-btn
                                  v-show="item.music.idmusic != 0"
                                  icon
                                  color="red"
                                  dark
                                  @click="
                                    delRepertoryItem(item.id, item.sequence)
                                  "
                                >
                                  <v-icon>mdi-delete</v-icon>
                                </v-btn>
                              </v-card-actions>
                            </v-card>
                          </v-list-item>
                        </v-list>
                      </v-card-text>
                    </v-card>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    <v-overlay v-show="loading">
      <v-progress-circular indeterminate></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  data: () => ({
    loadingSelectFields: false,
    loadingListRepertory: false,
    alert: false,
    items: [],
    loading: false,
    search: null,
    repertory: [],
    repertoryID: "",
    repertoryMoments: "",
    repertoryMomentsRules: [(v) => !!v || "Selecione um Momento"],
    repertoryMusic: "",
    repertoryMusicRules: [(v) => !!v || "Selecione um Música"],
    repertorySequence: "",
    maxSequence: null,
    moments: [],
    music: [],
  }),

  methods: {
    ...mapActions(["setUserNow", "setStartedRepertory"]),

    startRepertory: function () {
      let data = new FormData();
      data.append("idinscribe", this.inscribeID);
      axios(process.env.VUE_APP_URL + "startRepertory", {
        method: "POST",
        data: data,
      }).then((response) => {
        this.$swal(response.data.msg, "", response.data.icon);
        this.getRepertory();
      });
    },
    getRepertory: function () {
      this.loadingListRepertory = true;
      axios
        .get(
          process.env.VUE_APP_URL + "getRepertoryCustomers/" + this.inscribeID
        )
        .then((response) => {
          const resp = response.data;
          if (resp) {
            this.setStartedRepertory(true);
            this.repertory = response.data;
          } else {
            this.setStartedRepertory(false);
          }
          this.loadingListRepertory = false;
        });
    },
    addRepertoryItem: function () {
      if (this.repertoryMusic && this.repertoryMoments) {
        this.nextSequence();
        let data = new FormData();
        data.append("idrepertory", this.repertoryID);
        data.append("idmoments", this.repertoryMoments);
        data.append("idmusic", this.repertoryMusic.idmusic);
        data.append("sequence", this.repertorySequence);
        // console.log('Moments: ' + this.repertoryMoments)
        // console.log("Music: " + this.repertoryMusic.idmusic)
        // console.log("Repertory: " + this.repertoryID)
        // console.log("Sequence: " + this.repertorySequence)
        axios(process.env.VUE_APP_URL + "addRepertoryItem", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          this.getMaxSequence(this.repertoryID);
          this.getRepertory();
        });
      } else {
        this.alert = true;
        setInterval(() => (this.alert = false), 5000);
      }
    },
    delRepertoryItem: function (id, sequence) {
      axios
        .get(
          process.env.VUE_APP_URL + "deleteRepertoryItem/" + id + "/" + sequence
        )
        .then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          this.getMaxSequence(this.repertoryID);
          this.getRepertory();
        });
    },
    upRepertoryItemSequence: function (id, repertory, sequence) {
      this.loading = true;
      let data = new FormData();
      data.append("repertory", repertory);
      data.append("sequence", sequence);
      axios(process.env.VUE_APP_URL + "sequenceUp/" + id, {
        method: "POST",
        data: data,
      }).then((response) => {
        this.loading = false;
        this.getRepertory();
      });
    },
    downRepertoryItemSequence: function (id, repertory, sequence) {
      this.loading = true;
      let data = new FormData();
      data.append("repertory", repertory);
      data.append("sequence", sequence);
      axios(process.env.VUE_APP_URL + "sequenceDown/" + id, {
        method: "POST",
        data: data,
      }).then((response) => {
        this.loading = false;
        this.getRepertory();
      });
    },
    getRepertoryID: async function () {
      let response = await axios.get(
        process.env.VUE_APP_URL + "getRepertoryCustomersID/" + this.inscribeID
      );
      this.repertoryID = response.data;
      await this.getMaxSequence(response.data);
      // console.log("Repertory ID: " + response.data);
    },
    getMoments: function () {
      this.loadingSelectFields = true;
      axios
        .get(process.env.VUE_APP_URL + "getMomentsCustomers")
        .then((response) => {
          this.moments = response.data;
          this.loadingSelectFields = false;
        });
    },
    getMusic: function (id) {
      this.loadingSelectFields = true;
      axios
        .get(process.env.VUE_APP_URL + "getMusicCustomers/")
        .then((response) => {
          this.music = response.data;
          this.loadingSelectFields = false;
        });
    },
    getMaxSequence: async function (id) {
      let response = await axios.get(
        process.env.VUE_APP_URL + "getMaxSequence/" + id
      );
      this.maxSequence = response.data;
      // console.log("Max Sequence:" + response.data)
    },

    nextSequence() {
      this.repertorySequence = parseInt(this.maxSequence) + parseInt(1);
    },
  },

  created: function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
    }
    this.getRepertory();
    this.getMusic();
    this.getRepertoryID();
  },

  computed: {
    ...mapGetters(["userNow", "inscribeID", "startedRepertory"]),
  },
};
</script>