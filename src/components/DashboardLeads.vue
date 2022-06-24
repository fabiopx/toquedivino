<template>
  <div>
    <v-container class="leads">
      <!-- Leads Card -->
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar dark color="grey darken-4">
              <h3>Leads</h3>
              <v-spacer></v-spacer>
              <v-dialog
                v-model="dialogLeads"
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
                      @click="setCrud('c'), setDataPreInscribe()"
                    >
                      <v-icon>mdi-plus</v-icon> novo lead
                    </v-btn>
                  </v-toolbar-items>
                </template>
                <v-card>
                  <v-toolbar dark color="grey darken-4">
                    <v-btn
                      icon
                      dark
                      @click="
                        closeDialog('dialogLeads'), clearFormPreInscribe()
                      "
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title> Cadastrar Lead </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn dark text @click="saveLead()">
                        <v-icon class="ma-2">mdi-content-save</v-icon>
                        Salvar
                      </v-btn>
                    </v-toolbar-items>
                  </v-toolbar>
                  <v-card-text>
                    <v-form ref="formPreInscribe">
                      <v-container>
                        <v-row>
                          <v-col>
                            <v-menu
                              v-model="pickDatePreInscribe"
                              :close-on-content-click="false"
                              :nudge-right="40"
                              transition="scale-transition"
                              offset-y
                              min-width="auto"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="preInscribeDate"
                                  label="Selecione a data"
                                  prepend-icon="mdi-calendar"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                                ></v-text-field>
                              </template>
                              <v-date-picker
                                v-model="preInscribeDate"
                                @input="pickDatePreInscribe = false"
                              >
                              </v-date-picker>
                            </v-menu>
                          </v-col>
                          <v-col>
                            <v-menu
                              v-model="pickTimePreInscribe"
                              :close-on-content-click="false"
                              :nudge-right="40"
                              transition="scale-transition"
                              offset-y
                              min-width="200px"
                              min-height="200px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="preInscribeTime"
                                  label="Selecione o horário"
                                  prepend-icon="mdi-clock-time-four-outline"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                                >
                                </v-text-field>
                              </template>
                              <v-time-picker
                                v-if="pickTimePreInscribe"
                                v-model="preInscribeTime"
                                full-width
                                @input="pickTimePreInscribe = false"
                              >
                              </v-time-picker>
                            </v-menu>
                          </v-col>
                          <v-col>
                            <v-text-field
                              v-model="preInscribeOrigin"
                              label="Origem da lead"
                            ></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col>
                            <v-menu
                              offset-x
                              :close-on-content-click="false"
                              class="pa-3"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="preInscribeIp"
                                  label="IP do acesso (se houver)"
                                  v-bind="attrs"
                                  v-on="on"
                                ></v-text-field>
                              </template>
                              <v-card>
                                <v-card-text>
                                  <div>Digite o IP</div>
                                  <vue-ip
                                    :ip="ip"
                                    :on-change="onChangeIP"
                                    theme="material"
                                  ></vue-ip>
                                </v-card-text>
                              </v-card>
                            </v-menu>
                          </v-col>
                          <v-col>
                            <v-text-field
                              v-model="preInscribeAccountable"
                              label="Nome do responsável"
                              :rules="preInscribeAccountableRule"
                            ></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col>
                            <v-text-field
                              v-model="preInscribePhone"
                              label="Telefone"
                              v-mask="maskTel(preInscribePhone)"
                              :rules="preInscribePhoneRule"
                            ></v-text-field>
                          </v-col>
                          <v-col>
                            <v-text-field
                              v-model="preInscribeMobile"
                              label="Celular"
                              v-mask="maskTel(preInscribeMobile)"
                              :rules="preInscribeMobileRule"
                            ></v-text-field>
                          </v-col>
                          <v-col>
                            <v-text-field
                              v-model="preInscribeEmail"
                              label="E-mail"
                              :rules="preInscribeEmailRule"
                            ></v-text-field>
                          </v-col>
                        </v-row>
                      </v-container>
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
              <v-text-field
                v-model="searchPreInscribe"
                label="Busca"
                append-icon="mdi-magnify"
                class="mt-4"
              >
              </v-text-field>
              <v-data-table
                v-show="!firstLoad"
                :headers="headersPreInscribe"
                :items="preInscribe"
                :search="searchPreInscribe"
                :items-per-page="5"
              >
                <template v-slot:item.flag="{ item }">
                  <v-icon v-if="item.flag == 0" color="red"
                    >mdi-checkbox-blank-circle
                  </v-icon>
                  <v-icon v-else="item.flag == 1" color="green">
                    mdi-checkbox-marked-circle</v-icon
                  >
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        v-show="item.flag == 0"
                        color="primary"
                        icon
                        v-bind="attrs"
                        v-on="on"
                      >
                        <v-icon>mdi-briefcase-account</v-icon>
                      </v-btn>
                    </template>
                    <v-list>
                      <v-list-item
                        v-for="(listItem, i) in preInscribeMenuAction"
                        :key="i"
                        @click="
                          openBottomSheet('bsPreInscribe', listItem.content)
                        "
                      >
                        <v-list-item-icon>
                          <v-icon v-text="listItem.icon"></v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                          <v-list-item-title v-text="listItem.title">
                          </v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                  </v-menu>
                  <v-bottom-sheet v-model="bsPreInscribe">
                    <v-sheet class="text-center">
                      <v-btn
                        class="mt-6"
                        text
                        color="blue-grey"
                        @click="bsPreInscribe = !bsPreInscribe"
                      >
                        <v-icon>mdi-close</v-icon> close
                      </v-btn>
                      <div v-show="bsContent == 'whatsapp'" class="py-3">
                        <v-row>
                          <v-col md="6" offset-md="3">
                            <p>
                              Enviar mensagem por whatsApp para:
                              {{ item.mobile }}
                            </p>
                            <v-textarea
                              outlined
                              rows="1"
                              v-model="sendWhatsapp"
                            >
                            </v-textarea>
                            <v-btn
                              depressed
                              class="white--text"
                              color="blue-grey"
                              @click="
                                sendWhatsappPreInscribe(
                                  item.idpre_inscribe,
                                  item.mobile
                                )
                              "
                            >
                              <v-icon>mdi-whatsapp</v-icon>Abrir Whatsapp
                            </v-btn>
                          </v-col>
                        </v-row>
                      </div>
                      <div v-show="bsContent == 'email'" class="py-3">
                        <v-row>
                          <v-col md="6" offset-md="3">
                            <p>
                              Enviar e-mail para <b>{{ item.email }}</b>
                            </p>
                            <ckeditor
                              :editor="editor"
                              tag-name="textarea"
                              v-model="sendEmail"
                            ></ckeditor>
                            <!-- <v-textarea outlined rows="1" v-model="sendEmail" required></v-textarea> -->
                            <v-btn
                              depressed
                              class="white--text mt-2"
                              color="blue-grey"
                              @click="
                                sendEmailPreInscribe(
                                  item.idpre_inscribe,
                                  item.email
                                )
                              "
                            >
                              <v-icon>mdi-email-send</v-icon> Enviar
                            </v-btn>
                          </v-col>
                        </v-row>
                      </div>
                    </v-sheet>
                  </v-bottom-sheet>

                  <!-- <v-btn
                    v-show="item.flag == 0"
                    color="blue-grey"
                    icon
                    @click="addInscribe(item)"
                  >
                    <v-icon>mdi-folder-plus</v-icon>
                  </v-btn> -->
                  <!-- <v-btn
                    color="red"
                    icon
                    @click="getLogMarketing(item.idpre_inscribe)"
                  >
                    <v-icon>mdi-file-chart</v-icon>
                  </v-btn> -->
                  <v-dialog
                    v-model="dialogPreInscribeReport"
                    transition="dialog-bottom-transition"
                    max-width="600"
                  >
                    <v-card>
                      <v-toolbar color="blue-grey" dark
                        >Estatística de interações de marketing</v-toolbar
                      >
                      <v-card-text>
                        <v-skeleton-loader
                          v-if="firstLoad"
                          type="list-item-avatar-three-line"
                        >
                        </v-skeleton-loader>
                        <GChart
                          v-show="!firstLoad"
                          v-bind:type="chartPreInscribeReport.typeChart"
                          v-bind:data="chartPreInscribeReport.dataChart"
                        >
                        </GChart>
                      </v-card-text>
                      <v-card-actions>
                        <v-btn
                          depressed
                          @click="closeDialogPreInscribeReport()"
                        >
                          <v-icon>mdi-close</v-icon> Fechar
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <!-- End Leads Card -->
    </v-container>
  </div>
</template>

<script>
import VueIp from "vue-ip";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default {
  name: "DashboardLeads",
  components: {
    VueIp,
  },
  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      firstLoad: false,
      loadingVisible: false,
      dialogInscribe: false,
      dialogLeads: false,
      dialogPreInscribeReport: false,
      ip: "000.000.000.000",
      maskPhone: "(##) ####-####",
      maskMobile: "(##) #####-####",
      maskCep: "#####-###",
      maskCpf: "###.###.###-##",
      maskCnpj: "##.###.###/####-##",
      maskMoney: "######.##",
      maskBirthdate: "##/##/####",
      idPreInscribe: "",
      pickDatePreInscribe: false,
      pickTimePreInscribe: false,
      preInscribeDatetime: null,
      preInscribeDate: this.$moment().format("YY-mm-dd"),
      preInscribeTime: new Date().toLocaleString().substr(11, 8),
      pickIpPreInscribe: false,
      preInscribeIp: "",
      preInscribeIpRule: [(v) => !v || "IP inválido"],
      preInscribeAccountable: "",
      preInscribeAccountableRule: [
        (v) => !!v || "O campo NOME DO RESPONSÁVEL é requerido",
      ],
      preInscribeOrigin: "",
      preInscribeOriginRule: [(v) => !!v || "O campo ORIGEM pe requerido"],
      preInscribePhone: "",
      preInscribePhoneRule: [(v) => !!v || "O campo TELEFONE é requerido"],
      preInscribeMobile: "",
      preInscribeMobileRule: [(v) => !!v || "O campo CELULAR  é requerido"],
      preInscribeEmail: "",
      preInscribeEmailRule: [
        (v) => !!v || "O campo E-MAIL é requerido",
        (v) => /.+@.+/.test(v) || "Insira um E-mail válido",
      ],
      preInscribeFlag: null,
      searchPreInscribe: "",
      headersPreInscribe: [
        {
          text: "Data",
          value: "datetime",
        },
        {
          text: "Origem",
          value: "origin",
        },
        {
          text: "Nome",
          value: "accountable",
        },
        {
          text: "Telefone",
          value: "phone",
        },
        {
          text: "Celular",
          value: "mobile",
        },
        {
          text: "E-mail",
          value: "email",
        },
        {
          text: "Flag",
          value: "flag",
        },
        {
          text: "Ações",
          value: "actions",
          sortable: false,
        },
      ],
      preInscribe: [],
      preInscribeMenuAction: [
        {
          title: "Enviar E-mail",
          icon: "mdi-email-newsletter",
          content: "email",
        },
        { title: "Abrir WhatsApp", icon: "mdi-whatsapp", content: "whatsapp" },
      ],
      preInscribeAction: "",
      chartPreInscribeReport: {
        typeChart: "PieChart",
        dataChart: [],
      },
      pickDateInscribe: false,
      pickDateEvent: false,
      pickTimeEvent: false,
      bsPreInscribe: false,
      bsContent: "",
      sendWhatsapp: "",
      sendEmail: "",
      editor: ClassicEditor,
      editorData: "<p></p>",
      editorConfig: {},
    };
  },

  mounted() {},

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

    maskTel: function (phone) {
      if (!!phone) {
        return phone.length == 15 ? this.maskMobile : this.maskPhone;
      } else {
        return this.maskMobile;
      }
    },

    openBottomSheet: function (bs, content = null) {
      this[bs] = !this[bs];
      this.bsContent = content;
    },

    setDataPreInscribe: function () {
      this.preInscribeDatetime = new Date().toISOString().substr(0, 10);
      this.preInscribeOrigin = "Toque Divino";
    },

    clearFormPreInscribe: function () {
      this.$refs.formPreInscribe.reset();
      this.ip = "";
    },

    onChangeIP: function (ip, port, valid) {
      this.preInscribeIp = ip;
      console.log(ip, port, valid);
    },

    getLogMarketing: function (id) {
      this.dialogPreInscribeReport = true;
      this.firstLoad = true;
      axios
        .get(this.apiURL + "/leads/getLogMarketing/" + id)
        .then((response) => {
          this.chartPreInscribeReport.dataChart = response.data;
          this.firstLoad = false;
        });
    },

    closeDialogPreInscribeReport: function () {
      this.dialogPreInscribeReport = false;
      this.chartPreInscribeReport.dataChart = [];
    },

    getPreInscribe: async function () {
      this.firstLoad = true;
      const response = await axios.get(this.apiURL + "/leads/get");
      this.preInscribe = response.data;
      this.stopContentLoading();
    },

    saveLead: function () {
      this.closeDialog("dialogLeads");
      this.loadingVisible = true;
      let data = new FormData();
      data.append(
        "datetime",
        this.preInscribeDate + " " + this.preInscribeTime
      );
      data.append(
        "ip",
        this.preInscribeIp ? this.preInscribeIp : "000.000.000.000"
      );
      data.append("origin", this.preInscribe);
      data.append("accountable", this.preInscribeAccountable);
      data.append("phone", this.preInscribePhone);
      data.append("mobile", this.preInscribeMobile);
      data.append("email", this.preInscribeEmail);
      data.append(
        "flag",
        this.preInscribeFlag !== null ? this.preInscribeFlag : 0
      );

      if (this.crud == "c") {
        axios(this.apiURL + "/leads/create", {
          method: "POST",
          data: data,
        })
          .then((response) => {
            this.loadingVisible = false;
            this.clearFormPreInscribe();
            this.preInscribeDate = new Date().toISOString().substr(0, 10);
            this.preInscribeTime = new Date().toLocaleString().substr(11, 8);
            this.preInscribeIp = "";
            this.preInscribeAccountable = "";
            this.preInscribeOrigin = "";
            this.preInscribePhone = "";
            this.preInscribeMobile = "";
            this.preInscribeEmail = "";
            this.preInscribeFlag = null;
            this.preInscribe = [];
            this.$swal(response.data.msg, "", response.data.icon);
            this.getPreInscribe();
          })
          .catch((err) => {
            this.loadingVisible = false;
            this.$swal(err, "", "error");
          });
      } else if (this.crud == "u") {
        axios(this.apiURL + "/leads/update/" + this.idPreInscribe, {
          method: "POST",
          data: data,
        })
          .then((response) => {
            this.loadingVisible = false;
            this.clearFormPreInscribe();
            this.preInscribeDate = new Date().toISOString().substr(0, 10);
            this.preInscribeTime = new Date().toLocaleString().substr(11, 8);
            this.preInscribeIp = "";
            this.preInscribeAccountable = "";
            this.preInscribeOrigin = "";
            this.preInscribePhone = "";
            this.preInscribeMobile = "";
            this.preInscribeEmail = "";
            this.preInscribeFlag = null;
            this.preInscribe = [];
            this.$swal(response.data.msg, "", response.data.icon);
            this.getPreInscribe();
          })
          .catch((err) => {
            this.loadingVisible = false;
            this.$swal(err, "", "error");
          });
      }
    },

    sendEmailPreInscribe: function (id, email) {
      this.bsPreInscribe = false;
      this.loadingVisible = true;
      let data = new FormData();
      data.append(
        "datetime",
        new Date().toISOString().substr(0, 10) +
          " " +
          new Date().toLocaleString().substr(11, 8)
      );
      data.append("idpre_inscribe", id);
      data.append("email", email);
      data.append("msg", this.sendEmail);
      axios(this.apiURL + "/leads/sendEmail/", {
        method: "POST",
        data: data,
      }).then((response) => {
        this.loadingVisible = false;
        this.sendEmail - "";
        this.$swal(response.data.msg, "", response.data.icon);
      });
    },
    sendWhatsappPreInscribe: function (id, mobile) {
      this.bsPreInscribe = false;
      this.loadingVisible = true;
      let data = new FormData();
      data.append(
        "datetime",
        new Date().toISOString().substr(0, 10) +
          " " +
          new Date().toLocaleString().substr(11, 8)
      );
      data.append("idpre_inscribe", id);
      axios(this.apiURL + "/leads/sendWhatsapp", {
        method: "POST",
        data: data,
      }).then((response) => {
        this.loadingVisible = false;
        this.$swal({
          title: response.data.msg,
          icon: response.data.icon,
          confirmButtonText: "Abrir WhatsApp",
        }).then((result) => {
          if (result.isConfirmed) {
            let msg = encodeURI(this.sendWhatsapp);
            let number = mobile.replace(/\D/g, "");
            window.open("https://wa.me/55" + number + "/?text=" + msg);
          }
        });
      });
    },

    addInscribe: function (item) {
      this.dialogInscribe = true;
      this.crud = "c";
      this.inscribeDatetime = item.datetime;
      this.inscribeAccountable = item.accountable;
      this.inscribePhone = item.phone;
      this.inscribeMobile = item.mobile;
      this.inscribeEmail = item.email;
      this.preInscribeFlag = 1;
      this.idPreInscribe = item.idpre_inscribe;
      this.getFormations();
      this.getServices();
    },
  },

  created: async function () {
    await this.getPreInscribe();
  },
};
</script>

<style lang="scss" scoped>
</style>