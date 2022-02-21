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
                      <v-select
                        v-show="!loadingSelectFields"
                        v-model="repertoryMoments"
                        label="Momento"
                        :items="moments"
                        item-text="name"
                        item-value="idmoments"
                        @change="getMusic(repertoryMoments)"
                      >
                      </v-select>
                      <v-select
                        v-show="!loadingSelectFields"
                        v-model="repertoryMusic"
                        label="Música"
                        :items="music"
                        item-text="name"
                        item-value="idmusic"
                        return-object
                      ></v-select>
                      <p v-show="repertoryMusic">
                        Ouvir {{ repertoryMusic.name }}
                      </p>
                      <audio
                        v-show="repertoryMusic"
                        :src="repertoryMusic.url"
                        controls="controls"
                      ></audio>
                      <div v-show="repertoryMusic" class="small">
                        {{ repertoryMusic.url }}
                      </div>
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
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <v-card>
                      <v-card-title>Músicas Escolhidas</v-card-title>
                      <v-card-text>
                        <v-skeleton-loader
                          v-if="loadingListRepertory"
                          type="text@2, button"
                        ></v-skeleton-loader>
                        <v-list v-show="!loadingListRepertory">
                          <v-list-item v-for="(item, i) in repertory" :key="i">
                            <v-list-item-icon>
                              <v-icon outlined>mdi-bookmark-music</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                              <v-list-item-title>
                                <b>{{ item.moments.name }}:</b>
                                {{ item.music.name }}
                              </v-list-item-title>
                              <v-list-item-subtitle>
                                <audio
                                  :src="item.music.url"
                                  controls="controls"
                                  width="200"
                                ></audio>
                              </v-list-item-subtitle>
                              <v-list-item-action>
                                <v-btn
                                  v-show="item.music.idmusic != 0"
                                  depressed
                                  color="primary"
                                  dark
                                  @click="
                                    delRepertoryItem(
                                      item.music.idmusic,
                                      item.moments.idmoments
                                    )
                                  "
                                >
                                  <v-icon>mdi-delete</v-icon>
                                </v-btn>
                              </v-list-item-action>
                              <v-divider></v-divider>
                            </v-list-item-content>
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
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  data: () => ({
    loadingSelectFields: false,
    loadingListRepertory: false,
    startedRepertory: false,
    repertory: [],
    repertoryID: "",
    repertoryMoments: "",
    repertoryMomentsRules: [(v) => !!v || "Selecione um Momento"],
    repertoryMusic: "",
    repertoryMusicRules: [(v) => !!v || "Selecione um Música"],
    repertorySequence: "",
    moments: [],
    music: [],
  }),

  methods: {
    ...mapActions(["setUserNow"]),

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
				.get(process.env.VUE_APP_URL + "getRepertoryCustomers/" + this.inscribeID)
				.then((response) => {
					const resp = response.data;
					if (resp) {
						this.startedRepertory = true;
						this.repertory = response.data;
					} else {
						this.startedRepertory = false;
					}
					this.loadingListRepertory = false;
				});
		},
    addRepertoryItem: function () {
			if (this.$refs.formAddRepertoryItem.validate()) {
				let data = new FormData();
				data.append("idrepertory", this.repertoryID);
				data.append("idmoments", this.repertoryMoments);
				data.append("idmusic", this.repertoryMusic.idmusic);
				data.append("sequence", this.repertorySequence);
				axios(process.env.VUE_APP_URL + "addRepertoryItem", {
					method: "POST",
					data: data,
				}).then((response) => {
					this.$swal(response.data.msg, "", response.data.icon);
					this.getRepertory();
				});
			}
		},
    delRepertoryItem: function (music, moments) {
			axios
				.get(process.env.VUE_APP_URL + "deleteRepertoryItem/" + music + "/" + moments)
				.then((response) => {
					this.$swal(response.data.msg, "", response.data.icon);
					this.getRepertory();
				});
		},
		getRepertoryID: function () {
			axios
				.get(process.env.VUE_APP_URL + "getRepertoryCustomersID/" + this.inscribeID)
				.then((response) => {
					this.repertoryID = response.data;
				});
		},
		getMoments: function () {
			this.loadingSelectFields = true;
			axios.get(process.env.VUE_APP_URL + "getMomentsCustomers").then((response) => {
				this.moments = response.data;
				this.loadingSelectFields = false;
			});
		},
		getMusic: function (id) {
			this.loadingSelectFields = true;
			axios.get(process.env.VUE_APP_URL + "getMusicCustomers/" + id).then((response) => {
				this.music = response.data;
				this.loadingSelectFields = false;
			});
		},
  },

  created: function(){
    if(this.$session.exists()){
      this.setUserNow(this.$session.get('userData'))
    }

    this.getRepertory();
		this.getRepertoryID();
		this.getMoments();
    
  },

  computed: {
    ...mapGetters(["userNow", "inscribeID"]),
  },
};
</script>