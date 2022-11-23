<template>
  <div>
    <v-container fluid>
      <v-row>
        <v-col>
          <v-card>
            <v-toolbar color="grey darken-4" dark>
              <v-toolbar-title>
                <h3><v-icon class="mr-3">mdi-heart-circle</v-icon>Noivos</h3>
              </v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text>
              <v-skeleton-loader
                v-if="loadingEngagedFields"
                type="heading, avatar, text@5"
                loading
              >
              </v-skeleton-loader>
              <v-form v-show="!loadingEngagedFields" ref="formEngaged">
                <v-sheet color="grey lighten-4" class="mb-4" rounded="lg">
                  <v-container>
                    <v-row>
                      <v-col cols="12">
                        <h2>Dados da noiva</h2>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="6">
                        <v-avatar
                          size="80"
                          color="grey lighten-4"
                          @click="openUploadPhoto('engagedBrideUploadPhoto')"
                        >
                          <v-img
                            :src="engagedBrideBlob ? engagedBrideBlob : engagedBridePhoto"
                          ></v-img>
                        </v-avatar>
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-checkbox
                          color="grey darken-4"
                          v-model="engagedBrideResponsibleFor"
                          label="Responsável pelo contrato"
                        >
                        </v-checkbox>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" lg="4">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          label="Nome"
                          v-model="engagedBrideName"
                          :rules="engagedBrideNameRules"
                          required
                        >
                        </v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="4">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          v-mask="maskTel(engagedBridePhone)"
                          label="Telefone"
                          v-model="engagedBridePhone"
                          :rules="engagedBridePhoneRules"
                          required
                        >
                        </v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="4">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          v-mask="maskTel(engagedBrideMobile)"
                          label="Celular"
                          v-model="engagedBrideMobile"
                          :rules="engagedBrideMobileRules"
                          required
                        >
                        </v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="4" lg="2">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          label="CEP"
                          v-model="engagedBrideAddress.zipcode"
                          @blur="getCEP('engagedBrideAddress')"
                          :rules="engagedBrideAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="8" lg="6">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          :loading="inputLoading"
                          label="Logradouro"
                          v-model="engagedBrideAddress.street"
                          :rules="engagedBrideAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="2">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          label="Número"
                          v-model="engagedBrideAddress.number"
                          :rules="engagedBrideAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="2">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          label="Complemento"
                          v-model="engagedBrideAddress.complement"
                        >
                        </v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          :loading="inputLoading"
                          label="Bairro"
                          v-model="engagedBrideAddress.neighborhood"
                          :rules="engagedBrideAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          :loading="inputLoading"
                          label="Cidade"
                          v-model="engagedBrideAddress.city"
                          :rules="engagedBrideAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          :loading="inputLoading"
                          label="Estado"
                          v-model="engagedBrideAddress.state"
                          :rules="engagedBrideAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          :loading="inputLoading"
                          label="País"
                          v-model="engagedBrideAddress.country"
                          :rules="engagedBrideAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          v-model="engagedBrideCpf"
                          label="CPF"
                          v-mask="maskCpf"
                        >
                        </v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          v-model="engagedBrideRg"
                          label="RG"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          v-model="engagedBrideBirthdate"
                          label="Data de nascimento"
                          v-mask="maskBirthdate"
                          @blur="is18(engagedBrideBirthdate)"
                        >
                        </v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-female"
                          v-model="engagedBrideEmail"
                          label="E-mail"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-sheet>
                <v-sheet color="grey lighten-4" rounded="lg">
                  <v-container>
                    <v-row>
                      <v-col>
                        <h2>Dados do noivo</h2>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="6">
                        <v-avatar
                          size="80"
                          color="grey lighten-4"
                          @click="openUploadPhoto('engagedGroomUploadPhoto')"
                        >
                          <v-img
                            :src="engagedGroomBlob ? engagedGroomBlob : engagedGroomPhoto"
                          ></v-img>
                        </v-avatar>
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-checkbox
                          color="grey darken-4"
                          v-model="engagedGroomResponsibleFor"
                          label="Responsável pelo contrato"
                        >
                        </v-checkbox>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" lg="4">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          label="Nome"
                          v-model="engagedGroomName"
                          :rules="engagedGroomNameRules"
                          required
                        >
                        </v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="4">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          label="Telefone"
                          v-model="engagedGroomPhone"
                          v-mask="maskTel(engagedGroomPhone)"
                          :rules="engagedGroomPhoneRules"
                          required
                        >
                        </v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="4">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          label="Celular"
                          v-model="engagedGroomMobile"
                          :rules="engagedGroomMobileRules"
                          v-mask="maskTel(engagedGroomMobile)"
                          required
                        >
                        </v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="4" lg="2">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          label="CEP"
                          v-model="engagedGroomAddress.zipcode"
                          @blur="getCEP('engagedGroomAddress')"
                        >
                        </v-text-field>
                      </v-col>
                      <v-col cols="12" md="8" lg="6">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          :loading="inputLoading"
                          label="Logradouro"
                          v-model="engagedGroomAddress.street"
                          :rules="engagedGroomAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="2">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          label="Número"
                          v-model="engagedGroomAddress.number"
                          :rules="engagedGroomAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="2">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          label="Complemento"
                          v-model="engagedGroomAddress.complement"
                        >
                        </v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          :loading="inputLoading"
                          label="Bairro"
                          v-model="engagedGroomAddress.neighborhood"
                          :rules="engagedGroomAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          :loading="inputLoading"
                          label="Cidade"
                          v-model="engagedGroomAddress.city"
                          :rules="engagedGroomAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          :loading="inputLoading"
                          label="Estado"
                          v-model="engagedGroomAddress.state"
                          :rules="engagedGroomAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          :loading="inputLoading"
                          label="País"
                          v-model="engagedGroomAddress.country"
                          :rules="engagedGroomAddressRules"
                          required
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          v-model="engagedGroomCpf"
                          label="CPF"
                          v-mask="maskCpf"
                        >
                        </v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          v-model="engagedGroomRg"
                          label="RG"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          v-model="engagedGroomBirthdate"
                          label="Data de nascimento"
                          v-mask="maskBirthdate"
                        >
                        </v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" lg="3">
                        <v-text-field
                          prepend-inner-icon="mdi-human-male"
                          v-model="engagedGroomEmail"
                          label="E-mail"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-sheet>
              </v-form>
              <v-container v-if="selectEngaged">
                <v-row>
                  <v-col>
                    <v-sheet elevation="1" color="grey lighten-4" class="pa-4">
                      <h2>Serviços do casamento</h2>
                      Saber os demais serviços do casamento nos ajuda a alinhar horários,
                      posição da banda etc.
                      <v-btn
                        text
                        color="red darken-4"
                        dark
                        rounded
                        @click="formWeddingServicesAdd()"
                      >
                        <v-icon class="mr-3">mdi-plus-circle</v-icon>
                        Cadastrar
                      </v-btn>
                      <div v-if="weddingServices.length != 0">
                        <v-list two-line>
                          <v-list-item
                            v-for="wservice in weddingServices"
                            :key="wservice.idwedding_services"
                          >
                            <v-list-item-content>
                              <v-list-item-title>{{
                                wservice.companyname
                              }}</v-list-item-title>
                              <v-list-item-subtitle>
                                Endereço: {{ wservice.address }}<br />
                                Telefone: {{ wservice.phone }}<br />
                                Nome de contato: {{ wservice.contactname }}
                              </v-list-item-subtitle>
                            </v-list-item-content>
                            <v-list-item-action>
                              <v-btn
                                icon
                                @click="deleteWeddingService(wservice.idwedding_services)"
                                ><v-icon>mdi-delete</v-icon></v-btn
                              >
                              <v-btn icon @click="formWeddingServicesEdit(wservice)"
                                ><v-icon>mdi-pencil</v-icon></v-btn
                              >
                            </v-list-item-action>
                          </v-list-item>
                        </v-list>
                      </div>
                      <div class="pa-5 font-weight-bold" v-else>
                        Não existe empresa cadastrada
                      </div>
                    </v-sheet>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <v-sheet elevation="1" class="pa-4 grey lighten-4">
                      <h2>Redes Sociais</h2>
                      <v-form ref="formSocialNetworks">
                        <v-select
                          v-model="socialNetworksSel"
                          label="Selecione a Rede Social"
                          :items="socialNetworksItems"
                          item-text="name"
                          :rules="socialNetworksSelRule"
                          return-object
                        >
                          <template v-slot:append>
                            <v-icon>{{ socialNetworksSel.icon }}</v-icon>
                          </template>
                        </v-select>
                        <v-select
                          v-model="socialNetworksEngaged"
                          label="De quem é a Rede Social?"
                          :items="socialNetworksEngagedItems"
                          item-text="name"
                          :rules="socialNetworksEngagedRule"
                          return-object
                        ></v-select>
                        <v-text-field
                          v-model="socialNetworksLink"
                          label="Link do perfil público"
                          prefix="https://"
                          :rules="socialNetworksLinkRule"
                        ></v-text-field>
                      </v-form>
                      <v-btn
                        text
                        rounded
                        color="red darken-4"
                        @click="saveSocialNetworks()"
                      >
                        <v-icon v-if="!socialNetworksEdit" class="mr-3"
                          >mdi-plus-circle</v-icon
                        >
                        <v-icon v-if="socialNetworksEdit" class="mr-3"
                          >mdi-content-save</v-icon
                        >
                        <span v-if="!socialNetworksEdit">Adicionar</span>
                        <span v-if="socialNetworksEdit">Atualizar</span>
                      </v-btn>
                      <v-btn
                        v-if="socialNetworksEdit"
                        text
                        rounded
                        color="red darken-4"
                        @click="socialNetworksFormEmpty()"
                        >Limpar</v-btn
                      >
                      <v-divider class="mt-3 mb-3"></v-divider>
                      <v-chip
                        v-for="social in socialNetworks"
                        :key="social.id"
                        class="pa-5 mr-3"
                      >
                        <v-btn icon @click="openLink(social.link)"
                          ><v-icon>{{ social.icon }}</v-icon></v-btn
                        >
                        {{ social.name }}
                        {{ socialNetworksEngagedReturn(social.engaged) }}

                        <v-btn icon class="ml-3" @click="socialNetworksOpenEdit(social)"
                          ><v-icon>mdi-pencil</v-icon></v-btn
                        >
                        <v-btn icon @click="deleteSocialNetworks(social.id)"
                          ><v-icon>mdi-close</v-icon></v-btn
                        >
                      </v-chip>
                    </v-sheet>
                  </v-col>
                </v-row>
              </v-container>
              <v-dialog
                v-model="engagedBrideUploadPhoto"
                transition="dialog-bottom-transition"
                max-width="600"
                persistent
              >
                <v-card>
                  <v-toolbar color="primary" dark> Upload de foto da noiva </v-toolbar>
                  <v-card-text>
                    <v-file-input
                      v-model="currentFileBride"
                      label="Foto"
                      chips
                      @change="readEngagedPhoto(currentFileBride, 'engagedBrideBlob')"
                    >
                    </v-file-input>
                    <v-progress-linear
                      v-show="progressShow"
                      v-model="progressUpload"
                      class="grey--text"
                      height="20"
                    >
                      {{ progressUpload }} %
                    </v-progress-linear>
                    <v-alert
                      v-show="uploadSuccess"
                      type="success"
                      transition="fade-transition"
                    >
                      {{ uploadMsg }}
                    </v-alert>
                    <v-alert
                      v-show="uploadError"
                      type="error"
                      transition="fade-transition"
                    >
                      {{ uploadMsg }}
                    </v-alert>
                  </v-card-text>
                  <v-card-actions class="justify-end">
                    <v-btn
                      depressed
                      dark
                      color="primary"
                      @click="engagedBrideUploadPhoto = false"
                    >
                      Fechar</v-btn
                    >
                  </v-card-actions>
                </v-card>
              </v-dialog>
              <v-dialog
                v-model="engagedGroomUploadPhoto"
                transition="dialog-bottom-transition"
                max-width="600"
                persistent
              >
                <v-card>
                  <v-toolbar color="primary" dark> Upload de foto do noivo </v-toolbar>
                  <v-card-text>
                    <v-file-input
                      v-model="currentFileGroom"
                      label="Foto"
                      chips
                      @change="readEngagedPhoto(currentFileGroom, 'engagedGroomBlob')"
                    >
                    </v-file-input>
                    <v-progress-linear
                      v-show="progressShow"
                      v-model="progressUpload"
                      class="grey--text"
                      height="20"
                    >
                      {{ progressUpload }} %
                    </v-progress-linear>
                    <v-alert
                      v-show="uploadSuccess"
                      type="success"
                      transition="fade-transition"
                    >
                      {{ uploadMsg }}
                    </v-alert>
                    <v-alert
                      v-show="uploadError"
                      type="error"
                      transition="fade-transition"
                    >
                      {{ uploadMsg }}
                    </v-alert>
                  </v-card-text>
                  <v-card-actions class="justify-end">
                    <v-btn
                      depressed
                      dark
                      color="primary"
                      @click="engagedGroomUploadPhoto = false"
                    >
                      Fechar</v-btn
                    >
                  </v-card-actions>
                </v-card>
              </v-dialog>
              <v-dialog v-model="formWeddingServices">
                <v-card>
                  <v-toolbar color="grey darken-4" dark>
                    <v-btn icon @click="closeFormWeddingServices()"
                      ><v-icon>mdi-close</v-icon></v-btn
                    >
                    <v-toolbar-title
                      >Cadastrar demais serviços do casamento</v-toolbar-title
                    >
                  </v-toolbar>
                  <v-form ref="formWeddingService">
                    <v-container>
                      <v-row>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="weddingServiceCompanyName"
                            :rules="weddingServiceCompanyNameRule"
                            label="Nome da empresa"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="weddingServicePhone"
                            :rules="weddingServicePhoneRule"
                            v-mask="maskTel(weddingServicePhone)"
                            label="Telefone"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" md="8">
                          <v-text-field
                            v-model="weddingServiceAddress"
                            label="Endereço"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="weddingServiceContactName"
                            :rules="weddingServiceContactNameRule"
                            label="Nome de contato"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-btn
                            color="red darken-4"
                            class="mr-3 pa-8"
                            dark
                            @click="saveWeddingService()"
                            ><v-icon class="mr-3">mdi-check</v-icon>Cadastrar</v-btn
                          >
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-form>
                </v-card>
              </v-dialog>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                depressed
                dark
                large
                color="red darken-4"
                class="pa-8"
                @click="saveEngaged()"
              >
                <v-icon>mdi-content-save</v-icon> Salvar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

function toDateFormat(input) {
  var datePart = input.match(/\d+/g),
    year = datePart[0], // get only two digits
    month = datePart[1],
    day = datePart[2];

  return day + "/" + month + "/" + year;
}

function FormataStringData(data) {
  var dia = data.split("/")[0];
  var mes = data.split("/")[1];
  var ano = data.split("/")[2];

  return ano + "-" + ("0" + mes).slice(-2) + "-" + ("0" + dia).slice(-2);
}

function getAge(dataNasc) {
  var dataAtual = new Date();

  var anoAtual = dataAtual.getFullYear();

  var anoNascParts = dataNasc.split("/");

  var diaNasc = anoNascParts[0];

  var mesNasc = anoNascParts[1];

  var anoNasc = anoNascParts[2];

  var idade = anoAtual - anoNasc;

  var mesAtual = dataAtual.getMonth() + 1;

  //Se mes atual for menor que o nascimento, nao fez aniversario ainda;

  if (mesAtual < mesNasc) {
    idade--;
  } else {
    //Se estiver no mes do nascimento, verificar o dia

    if (mesAtual == mesNasc) {
      if (new Date().getDate() < diaNasc) {
        //Se a data atual for menor que o dia de nascimento ele ainda nao fez aniversario

        idade--;
      }
    }
  }

  return idade;
}

function convertToMMDDYYYY(date) {
  return date[1] + "-" + date[0] + "-" + date[2];
}

export default {
  data: () => ({
    apiURL: process.env.VUE_APP_URL,
    crud: "c",
    inputLoading: false,
    showTabInscribe: "inscribe",
    tabInscribeTitle: "Dados do cadastro",
    showPassword: false,
    alert: false,
    alertMsg: "",
    currentFileBride: undefined,
    currentFileGroom: undefined,
    progressUpload: 0,
    progressShow: false,
    uploadSuccess: false,
    uploadError: false,
    uploadMsg: "",
    maskPhone: "(##) ####-####",
    maskMobile: "(##) #####-####",
    maskCpf: "###.###.###-##",
    maskCnpj: "##.###.###/####-##",
    maskMoney: "######.##",
    maskBirthdate: "##/##/####",
    maskCep: "#####-###",
    buscarEndereco: false,
    tooltipEndereco: false,
    services: [],
    formation: [],
    loadingInscribeFields: false,
    inscribeID: "",
    inscribeAccountable: "",
    inscribePhone: "",
    inscribeMobile: "",
    inscribeAddress: {
      street: "",
      number: "",
      complement: "",
      neighborhood: "",
      city: "",
      zipcode: "",
      state: "",
      country: "",
    },
    inscribeCpf: "",
    inscribeRg: "",
    inscribeStatus: "",
    inscribeService: "",
    inscribeFormation: "",
    inscribeServiceTax: "",
    inscribeAgree: false,
    loadingEngagedFields: false,
    selectEngaged: false,
    engagedID: "",
    engagedBrideAccountable: false,
    engagedGroomAccountable: false,
    engagedBrideName: "",
    engagedBrideNameRules: [(v) => !!v || "O campo NOME DA NOIVA é requerido"],
    engagedBridePhoto: process.env.VUE_APP_IMGPATH + "woman.png",
    engagedBrideBlob: "",
    engagedBrideAddress: {
      street: "",
      number: "",
      complement: "",
      neighborhood: "",
      city: "",
      zipcode: "",
      state: "",
      country: "",
    },
    engagedBrideAddressRules: [(v) => !!v || "Este campo é requerido"],
    engagedBridePhone: "",
    engagedBridePhoneRules: [(v) => !!v || "O campo TELEFONE DA NOIVA é requerido"],
    engagedBrideMobile: "",
    engagedBrideMobileRules: [(v) => !!v || "O campo CELULAR DA NOIVA é requerido"],
    engagedBrideCpf: "",
    engagedBrideRg: "",
    engagedBrideBirthdate: "",
    engagedBrideEmail: "",
    engagedBrideResponsibleFor: false,
    engagedGroomName: "",
    engagedGroomNameRules: [(v) => !!v || "O campo NOME DO NOIVO é requerido"],
    engagedGroomPhoto: process.env.VUE_APP_IMGPATH + "man.png",
    engagedGroomBlob: "",
    engagedGroomAddress: {
      street: "",
      number: "",
      complement: "",
      neighborhood: "",
      city: "",
      zipcode: "",
      state: "",
      country: "",
    },
    engagedGroomAddressRules: [(v) => !!v || "Este campo é requerido"],
    engagedGroomPhone: "",
    engagedGroomPhoneRules: [(v) => !!v || "O campo TELEFONE DO NOIVO é requerido"],
    engagedGroomMobile: "",
    engagedGroomMobileRules: [(v) => !!v || "O campo CELULAR DO NOIVO é requerido"],
    engagedGroomCpf: "",
    engagedGroomRg: "",
    engagedGroomBirthdate: "",
    engagedGroomEmail: "",
    engagedGroomResponsibleFor: false,
    engagedBrideUploadPhoto: false,
    engagedGroomUploadPhoto: false,
    loadingSelectFields: false,
    formWeddingServices: false,
    loadingWeddingServices: false,
    weddingServiceID: "",
    weddingServiceCompanyName: "",
    weddingServiceCompanyNameRule: [(v) => !!v || "O campo NOME DA EMPRESA é requerido"],
    weddingServiceAddress: "",
    weddingServicePhone: "",
    weddingServicePhoneRule: [(v) => !!v || "O campo TELEFONE é requerido"],
    weddingServiceContactName: "",
    weddingServiceContactNameRule: [(v) => !!v || "O campo NOME DE CONTATO é requerido"],
    weddingServices: [],
    socialNetworks: [],
    socialNetworksID: "",
    socialNetworksEdit: false,
    socialNetworksSel: { name: "", icon: "" },
    socialNetworksSelRule: [(v) => !!v || "Selecione uma Rede Social"],
    socialNetworksEngaged: { id: "", name: "" },
    socialNetworksEngagedRule: [(v) => !!v || "Diga de quem é a rede social"],
    socialNetworksEngagedItems: [
      {
        id: 1,
        name: "do Noivo",
      },
      {
        id: 2,
        name: "da Noiva",
      },
      {
        id: 3,
        name: "da Noiva e do Noivo",
      },
    ],
    socialNetworksLink: "",
    socialNetworksLinkRule: [(v) => !!v || "Digite o link da Rede Social"],
    socialNetworksEngagedID: "",
    socialNetworksItems: [
      {
        name: "Facebook",
        icon: "mdi-facebook",
      },
      {
        name: "Instagram",
        icon: "mdi-instagram",
      },
      {
        name: "Twitter",
        icon: "mdi-twitter",
      },
      {
        name: "LinkedIn",
        icon: "mdi-linkedin",
      },
      {
        name: "Pinterest",
        icon: "mdi-pinterest",
      },
    ],
  }),

  methods: {
    ...mapActions(["setUserNow", "setSkipEngaged"]),
    resetAllVars: function () {
      this.currentFileBride = undefined;
      this.currentFileGroom = undefined;
      this.inscribeAccountable = "";
      this.inscribePhone = "";
      this.inscribeMobile = "";
      this.inscribeAddress = {
        street: "",
        number: "",
        complement: "",
        neighborhood: "",
        city: "",
        zipcode: "",
        state: "",
        country: "",
      };
      this.inscribeCpf = "";
      this.inscribeRg = "";
      this.inscribeService = "";
      this.inscribeFormation = "";
      this.inscribeServiceTax = "";
      this.services = [];
      this.formation = [];
      this.eventID = "";
      this.eventName = "";
      this.eventDate = "";
      this.eventTime = "";
      this.eventAddress = {
        street: "",
        number: "",
        complement: "",
        neighborhood: "",
        city: "",
        zipcode: "",
        state: "",
        country: "",
      };
      this.engagedID = "";
      this.engagedBrideName = "";
      this.engagedBrideAddress = {
        street: "",
        number: "",
        complement: "",
        neighborhood: "",
        city: "",
        zipcode: "",
        state: "",
        country: "",
      };
      this.engagedBridePhone = "";
      this.engagedBrideMobile = "";
      this.engagedBrideCpf = "";
      this.engagedBrideRg = "";
      this.engagedBrideBirthdate = "";
      this.engagedBrideEmail = "";
      (this.engagedBrideResponsibleFor = false), (this.engagedGroomName = "");
      this.engagedGroomAddress = {
        street: "",
        number: "",
        complement: "",
        neighborhood: "",
        city: "",
        zipcode: "",
        state: "",
        country: "",
      };
      this.engagedGroomPhone = "";
      this.engagedGroomMobile = "";
      this.engagedGroomCpf = "";
      (this.engagedGroomRg = ""), (this.engagedGroomBirthdate = "");
      this.engagedGroomEmail = "";
      (this.engagedGroomResponsibleFor = false), (this.graduationCommitteName = "");
      this.graduationCommitteMember = [];
    },
    maskTel: function (phone) {
      if (!!phone) {
        return phone.length == 15 ? this.maskMobile : this.maskPhone;
      } else {
        return this.maskMobile;
      }
    },
    is18: function (date) {
      if (getAge(date) < 18) {
        this.$swal("Responsável precisa ter mais que 18 anos");
      }
    },
    openUploadPhoto: function (profile) {
      this[profile] = true;
      this.currentFileBride = undefined;
      this.currentFileGroom = undefined;
      this.uploadMsg = "";
      this.uploadSuccess = false;
      this.uploadError = false;
    },
    readEngagedPhoto: function (file, profile) {
      if (file) {
        this[profile] = URL.createObjectURL(file);
        this.progressShow = true;
        this.photo = profile == "engagedBrideBlob" ? "woman.png" : "man.png";

        let data = new FormData();
        data.append("file", file);

        axios
          .post(this.apiURL + "/uploads/uploadPhoto", data, {
            onUploadProgress: (event) => {
              const totalLength = event.lengthComputable
                ? event.total
                : event.target.getResponseHeader("content-length") ||
                  event.target.getResponseHeader("x-decompressed-content-length");
              console.log("onUploadProgress", totalLength);
              if (totalLength !== null) {
                this.progressUpload = Math.round((event.loaded * 100) / totalLength);
              }
            },
          })
          .then((response) => {
            response.data.type == "success"
              ? (this.uploadSuccess = true)
              : (this.uploadError = true);
            this.uploadMsg = response.data.msg;
            this.progressShow = false;
          })
          .catch((error) => {
            this.uploadError = true;
            this.uploadMsg = error;
            this.progressShow = false;
          });
      } else {
        this[profile] = process.env.VUE_APP_IMGPATH + this.photo;
        this.progressShow = false;
        this.uploadMsg = "";
        this.uploadSuccess = false;
        this.uploadError = false;
      }
    },
    selectAccountableBride: function () {
      if (this.engagedBrideAccountable) {
        this.engagedBrideName = this.inscribeAccountable;
        this.engagedBridePhone = this.inscribePhone;
        this.engagedBrideMobile = this.inscribeMobile;
        this.engagedGroomAccountable = false;
      } else {
        this.engagedBrideName = "";
        this.engagedBridePhone = "";
        this.engagedBrideMobile = "";
      }
    },
    selectAccountableGroom: function () {
      if (this.engagedGroomAccountable) {
        this.engagedGroomName = this.inscribeAccountable;
        this.engagedGroomPhone = this.inscribePhone;
        this.engagedGroomMobile = this.inscribeMobile;
        this.engagedBrideAccountable = false;
      } else {
        this.engagedGroomName = "";
        this.engagedGroomPhone = "";
        this.engagedGroomMobile = "";
      }
    },
    formWeddingServicesAdd: function () {
      this.crud = "c";
      this.formWeddingServices = true;
    },
    formWeddingServicesEdit: function (obj) {
      this.crud = "u";
      this.formWeddingServices = true;
      this.weddingServiceID = obj.idwedding_services;
      this.weddingServiceCompanyName = obj.companyname;
      this.weddingServiceAddress = obj.address;
      this.weddingServicePhone = obj.phone;
      this.weddingServiceContactName = obj.contactname;
      console.log(obj.idwedding_services);
    },
    closeFormWeddingServices: function () {
      this.crud = "";
      this.weddingServiceID = "";
      this.weddingServiceCompanyName = "";
      this.weddingServiceAddress = "";
      this.weddingServicePhone = "";
      this.weddingServiceContactName = "";
      this.formWeddingServices = false;
    },
    socialNetworksEngagedReturn: function (n) {
      if (n == 1) return "do Noivo";
      if (n == 2) return "da Noiva";
      if (n == 3) return "da Noiva e do Noivo";
    },
    socialNetworksFormEmpty: function () {
      this.$refs.formSocialNetworks.reset();
      this.socialNetworksSel = { name: "", icon: "" };
      this.socialNetworksEngaged = { id: "", name: "" };
      this.socialNetworksLink = "";
      this.socialNetworksEdit = false;
    },
    socialNetworksOpenEdit: function (social) {
      this.socialNetworksEdit = true;
      this.socialNetworksID = social.id;
      this.socialNetworksSel = { name: social.name, icon: social.icon };
      this.socialNetworksEngaged = {
        id: social.engaged,
        name: this.socialNetworksEngagedReturn(social.engaged),
      };
      this.socialNetworksLink = social.link;
    },
    openLink: function (link) {
      window.open("https://" + link);
    },
    getAddressData: function (addressData, placeResultData, id) {
      this.inscribeAddress.street = addressData.route;
      this.inscribeAddress.number = addressData.street_number
        ? addressData.street_number
        : "";
      const address = placeResultData.formatted_address.split("-");
      const neighborhood = address[1].split(",");
      this.inscribeAddress.neighborhood = neighborhood[0];
      this.inscribeAddress.state = addressData.administrative_area_level_1;
      this.inscribeAddress.country = addressData.country;
      this.inscribeAddress.city = addressData.administrative_area_level_2;
      this.inscribeAddress.zipcode = addressData.postal_code
        ? addressData.postal_code
        : "";
      this.buscarEndereco = false;
    },
    getCEP: function (obj) {
      var cep = this[obj].zipcode.replace(/\D/g, "");

      this.inputLoading = true;

      this[obj].street = "Carregando...";
      this[obj].neighborhood = "Carregando...";
      this[obj].city = "Carregando...";
      this[obj].state = "Carregando...";
      this[obj].country = "Carregando...";

      if (cep != "") {
        var validacep = /^[0-9]{8}$/;
        if (validacep.test(cep)) {
          axios
            .get("https://viacep.com.br/ws/" + cep + "/json/")
            .then((response) => {
              this.inputLoading = false;
              this[obj].street = response.data.logradouro;
              this[obj].neighborhood = response.data.bairro;
              this[obj].city = response.data.localidade;
              this[obj].state = response.data.uf;
              this[obj].country = "Brasil";
            })
            .catch((err) => {
              this.inputLoading = false;
              this.$swal(err.message, "", "error");
            });
        } else {
          this.inputLoading = false;
          this.$swal("CEP inválido", "", "error");
          this[obj].zipcode = "";
        }
      } else {
        this.$swal("Por favor digite CEP!", "", "error");
      }
    },
    getInscribe: async function () {
      this.loadingInscribeFields = true;
      const response = await axios.get(
        this.apiURL + "/inscribes/getCustomers/" + this.userNow.id
      );
      const resp = response.data;
      if (resp) {
        this.inscribeID = resp.idinscribe;
        this.inscribeAccountable = resp.accountable;
        this.inscribePhone = resp.phone;
        this.inscribeMobile = resp.mobile;
        this.inscribeAddress = resp.address;
        this.inscribeCpf = resp.cpf;
        this.inscribeRg = resp.rg;
        this.inscribeStatus = resp.status;
        this.inscribeService = resp.service;
        this.inscribeFormation = resp.formation;
        this.eventName = resp.service.name;
      }
      this.loadingInscribeFields = false;
      this.loadingAgreementValues = false;
    },
    getEngaged: async function () {
      this.loadingEngagedFields = true;
      const response = await axios.get(
        this.apiURL + "/engagedes/getCustomers/" + this.inscribeID
      );
      const resp = response.data;
      if (resp) {
        this.crud = "u";
        this.engagedID = resp.idengaged;
        this.engagedGroomName = resp.groom_name;
        this.engagedGroomAddress = resp.groom_address;
        this.engagedGroomPhone = resp.groom_phone;
        this.engagedGroomMobile = resp.groom_mobile;
        this.engagedGroomPhoto = resp.groom_photo
          ? resp.groom_photo
          : process.env.VUE_APP_IMGPATH + "man.png";
        this.engagedGroomCpf = resp.groom_cpf;
        this.engagedGroomRg = resp.groom_rg;
        this.engagedGroomBirthdate = toDateFormat(resp.groom_birthdate);
        this.engagedGroomEmail = resp.groom_email;
        this.engagedGroomResponsibleFor = resp.groom_responsible_for;
        this.engagedBrideName = resp.bride_name;
        this.engagedBrideAddress = resp.bride_address;
        this.engagedBridePhone = resp.bride_phone;
        this.engagedBrideMobile = resp.bride_mobile;
        this.engagedBridePhoto = resp.bride_photo
          ? resp.bride_photo
          : process.env.VUE_APP_IMGPATH + "woman.png";
        this.engagedBrideCpf = resp.bride_cpf;
        this.engagedBrideRg = resp.bride_rg;
        this.engagedBrideBirthdate = toDateFormat(resp.bride_birthdate);
        this.engagedBrideEmail = resp.bride_email;
        this.engagedBrideResponsibleFor = resp.bride_responsible_for;
        this.selectEngaged = resp.selectEngaged;
        await this.getWeddingServices(resp.idengaged);
        await this.getSocialNetworks(resp.idengaged);
      } else {
        this.crud = "c";
        this.selectEngaged = false;
        // console.log(this.selectEngaged);
      }
      this.loadingEngagedFields = false;
    },
    getWeddingServices: async function (id) {
      this.loadingWeddingServices = true;
      const response = await axios.get(
        this.apiURL + "/engagedes/getWeddingServices/" + id
      );
      this.weddingServices = response.data;
      this.loadingWeddingServices = false;
    },
    getSocialNetworks: async function (id) {
      const response = await axios.get(
        this.apiURL + "/engagedes/getSocialNetworks/" + id
      );
      this.socialNetworks = response.data;
    },
    saveEngaged: function () {
      let data = new FormData();
      data.append("groom_name", this.engagedGroomName);
      data.append("groom_address", JSON.stringify(this.engagedGroomAddress));
      data.append("groom_phone", this.engagedGroomPhone);
      data.append("groom_mobile", this.engagedGroomMobile);
      data.append(
        "groom_photo",
        this.currentFileGroom
          ? process.env.VUE_APP_UPLOAD + this.currentFileGroom.name
          : this.engagedGroomPhoto
      );
      data.append("groom_cpf", this.engagedGroomCpf);
      data.append("groom_rg", this.engagedGroomRg);
      data.append("groom_birthdate", FormataStringData(this.engagedGroomBirthdate));
      data.append("groom_email", this.engagedGroomEmail);
      data.append("groom_responsible_for", this.engagedGroomResponsibleFor);
      data.append("bride_name", this.engagedBrideName);
      data.append("bride_address", JSON.stringify(this.engagedBrideAddress));
      data.append("bride_phone", this.engagedBridePhone);
      data.append("bride_mobile", this.engagedBrideMobile);
      data.append(
        "bride_photo",
        this.currentFileBride
          ? process.env.VUE_APP_UPLOAD + this.currentFileBride.name
          : this.engagedBridePhoto
      );
      data.append("bride_cpf", this.engagedBrideCpf);
      data.append("bride_rg", this.engagedBrideRg);
      data.append("bride_birthdate", FormataStringData(this.engagedBrideBirthdate));
      data.append("bride_email", this.engagedBrideEmail);
      data.append("bride_responsible_for", this.engagedBrideResponsibleFor);
      data.append("idinscribe", this.inscribeID);

      if (this.crud == "c") {
        axios(this.apiURL + "/engagedes/createCustomers", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          if (this.access.skipEngaged) {
            this.$router.push("/customer/agreement");
          } else {
            this.setSkipEngaged(true);
            this.getEngaged();
          }
        });
      } else if (this.crud == "u") {
        axios(this.apiURL + "/engagedes/updateCustomers/" + this.engagedID, {
          method: "POST",
          data: data,
        }).then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          this.getEngaged();
        });
      }
    },
    saveWeddingService: function () {
      if (this.$refs.formWeddingService.validate()) {
        let data = new FormData();
        data.append("companyname", this.weddingServiceCompanyName);
        data.append("address", this.weddingServiceAddress);
        data.append("phone", this.weddingServicePhone);
        data.append("contactname", this.weddingServiceContactName);
        data.append("engaged_idengaged", this.engagedID);
        if (this.crud == "c") {
          axios(this.apiURL + "/engagedes/createWeddingServices", {
            method: "POST",
            data: data,
          }).then((response) => {
            this.closeFormWeddingServices();
            this.$swal(response.data.msg, "", response.data.icon);
            this.getWeddingServices(this.engagedID);
          });
        } else if (this.crud == "u") {
          axios(
            this.apiURL + "/engagedes/updateWeddingServices/" + this.weddingServiceID,
            {
              method: "POST",
              data: data,
            }
          ).then((response) => {
            this.closeFormWeddingServices();
            this.$swal(response.data.msg, "", response.data.icon);
            this.getWeddingServices(this.engagedID);
          });
        }
      }
    },
    saveSocialNetworks: function () {
      if (this.$refs.formSocialNetworks.validate()) {
        let data = new FormData();
        data.append("name", this.socialNetworksSel.name);
        data.append("icon", this.socialNetworksSel.icon);
        data.append("engaged", this.socialNetworksEngaged.id);
        data.append("link", this.socialNetworksLink);
        data.append("engaged_idengaged", this.engagedID);
        if (!this.socialNetworksEdit) {
          axios(this.apiURL + "/engagedes/createSocialNetworks", {
            method: "POST",
            data: data,
          }).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            this.socialNetworksFormEmpty();
            this.getSocialNetworks(this.engagedID);
          });
        } else if (this.socialNetworksEdit) {
          axios(
            this.apiURL + "/engagedes/updateSocialNetworks/" + this.socialNetworksID,
            {
              method: "POST",
              data: data,
            }
          ).then((response) => {
            this.$swal(response.data.msg, "", response.data.icon);
            this.socialNetworksEdit = false;
            this.socialNetworksFormEmpty();
            this.getSocialNetworks(this.engagedID);
          });
        }
      }
    },
    deleteWeddingService: function (id) {
      this.loading = true;
      axios
        .get(this.apiURL + "/engagedes/deleteWeddingService/" + id)
        .then((response) => {
          this.loading = false;
          this.$swal(response.data.msg, "", response.data.icon);
          this.getWeddingServices(this.engagedID);
        });
    },
    deleteSocialNetworks: async function (id) {
      const response = await axios.get(
        this.apiURL + "/engagedes/deleteSocialNetworks/" + id
      );
      this.$swal(response.data.msg, "", response.data.icon);
      this.getSocialNetworks(this.engagedID);
    },
  },

  computed: {
    ...mapGetters(["userNow", "isAgreement", "access"]),
  },

  created: async function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
    }
    await this.getInscribe();
    await this.getEngaged();
    // console.log(this.weddingServices.length)
  },
};
</script>
