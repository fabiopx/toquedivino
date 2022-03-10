<template>
  <div>
    <v-container fluid>
      <v-row>
        <v-col>
          <p class="text-h4 white--text">
            <v-icon color="white">mdi-folder-information</v-icon> Cadastro
          </p>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <v-tabs background-color="grey darken-4" dark show-arrows>
            <v-tab @click="activeTabInscribe('inscribe')">
              <v-icon>mdi-folder-text</v-icon> Cadastro
            </v-tab>
            <v-tab @click="activeTabInscribe('events')">
              <v-icon>mdi-folder-clock</v-icon> Evento
            </v-tab>
            <v-tab @click="activeTabInscribe('engaged')">
              <v-icon>mdi-human-male-female</v-icon> Noivos
            </v-tab>
            <v-tab @click="activeTabInscribe('committe')">
              <v-icon>mdi-school</v-icon> Comissão de formatura
            </v-tab>
          </v-tabs>
          <v-card>
            <v-toolbar color="grey darken-4" dark>
              <v-toolbar-title>
                <span class="small">{{ tabInscribeTitle }}</span>
              </v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text v-show="showTabInscribe == 'inscribe'">
              <v-skeleton-loader
                v-if="loadingInscribeFields"
                type="text@5"
                loading
              >
              </v-skeleton-loader>
              <v-form v-show="!loadingInscribeFields" ref="formInscribe">
                <v-container>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Nome do Responsável"
                        v-model="inscribeAccountable"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Telefone"
                        v-model="inscribePhone"
                        v-mask="maskTel(inscribePhone)"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="4" lg="2">
                      <v-text-field
                        label="CEP"
                        v-model="inscribeAddress.zipcode"
                        v-mask="maskCep"
                        @blur="getCEP('inscribeAddress')"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="8" lg="6">
                      <v-text-field
                        label="Logradouro"
                        v-model="inscribeAddress.street"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" lg="2">
                      <v-text-field
                        label="Número"
                        v-model="inscribeAddress.number"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" lg="2">
                      <v-text-field
                        label="Complemento"
                        v-model="inscribeAddress.complement"
                      >
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Bairro"
                        v-model="inscribeAddress.neighborhood"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Cidade"
                        v-model="inscribeAddress.city"
                      >
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Estado"
                        v-model="inscribeAddress.state"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="País"
                        v-model="inscribeAddress.country"
                      >
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="CPF"
                        v-model="inscribeCpf"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="RG"
                        v-model="inscribeRg"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-select
                        v-model="inscribeService.idservice"
                        label="Serviço"
                        :items="services"
                        item-text="name"
                        item-value="idservice"
                        required
                      >
                      </v-select>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-select
                        v-model="inscribeFormation.idformation"
                        label="Formação"
                        :items="formation"
                        item-text="name"
                        item-value="idformation"
                        required
                      >
                      </v-select>
                    </v-col>
                  </v-row>
                </v-container>
              </v-form>
            </v-card-text>
            <v-card-text v-show="showTabInscribe == 'events'">
              <v-skeleton-loader
                v-if="loadingEventFields"
                type="text@4"
                loading
              >
              </v-skeleton-loader>
              <v-form v-show="!loadingEventFields" ref="formEvents">
                <v-container>
                  <v-row>
                    <v-col cols="12" lg="4">
                      <v-text-field
                        label="Evento"
                        v-model="eventName"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" lg="4">
                      <v-menu
                        v-model="pickDateEvent"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="eventDate"
                            label="Data do evento"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                          >
                          </v-text-field>
                        </template>
                        <v-date-picker
                          v-model="eventDate"
                          @input="pickDateEvent = false"
                        >
                        </v-date-picker>
                      </v-menu>
                    </v-col>
                    <v-col cols="12" md="6" lg="4">
                      <v-menu
                        ref="menu"
                        v-model="pickTimeEvent"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        :return-value.sync="eventTime"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="eventTime"
                            label="Horário do Evento"
                            prepend-icon="mdi-calendar-clock"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                          >
                          </v-text-field>
                        </template>
                        <v-time-picker
                          v-if="pickTimeEvent"
                          v-model="eventTime"
                          full-width
                          @click:minute="$refs.menu.save(eventTime)"
                        >
                        </v-time-picker>
                      </v-menu>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="4" lg="2">
                      <v-text-field
                        label="CEP"
                        v-model="eventAddress.zipcode"
                        v-mask="maskCep"
                        @blur="getCEP('eventAddress')"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="8" lg="6">
                      <v-text-field
                        label="Logradouro"
                        v-model="eventAddress.street"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" lg="2">
                      <v-text-field
                        label="Número"
                        v-model="eventAddress.number"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" lg="2">
                      <v-text-field
                        label="Complemento"
                        v-model="eventAddress.complement"
                      >
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Bairro"
                        v-model="eventAddress.neighborhood"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="Cidade"
                        v-model="eventAddress.city"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field label="Estado" v-model="eventAddress.state">
                      </v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field label="País" v-model="eventAddress.country">
                      </v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-form>
            </v-card-text>
            <v-card-text v-show="showTabInscribe == 'engaged'">
              <v-skeleton-loader
                v-if="loadingEngagedFields"
                type="heading, avatar, text@5"
                loading
              >
              </v-skeleton-loader>
              <v-form v-show="!loadingEngagedFields" ref="formEngaged">
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
                          :src="
                            engagedBrideBlob
                              ? engagedBrideBlob
                              : engagedBridePhoto
                          "
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
                          :src="
                            engagedGroomBlob
                              ? engagedGroomBlob
                              : engagedGroomPhoto
                          "
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
                <v-container v-if="selectEngaged">
                  <v-row>
                    <v-col>
                      <v-sheet
                        elevation="1"
                        color="grey lighten-4"
                        class="pa-4"
                      >
                        <h2>Serviços do casamento</h2>
                        Saber os demais serviços do casamento nos ajuda a
                        alinhar horários, posição da banda etc.
                        <v-btn
                          text
                          color="grey darken-4"
                          dark
                          @click="formWeddingServicesAdd()"
                        >
                          <v-icon>mdi-plus-circle</v-icon> Cadastrar
                        </v-btn>
                        <v-divider></v-divider>
                        <div v-if="weddingServices.lenght != 0">
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
                                  @click="
                                    deleteWeddingService(
                                      wservice.idwedding_services
                                    )
                                  "
                                  ><v-icon>mdi-delete</v-icon></v-btn
                                >
                                <v-btn
                                  icon
                                  @click="formWeddingServicesEdit(wservice)"
                                  ><v-icon>mdi-pencil</v-icon></v-btn
                                >
                              </v-list-item-action>
                            </v-list-item>
                          </v-list>
                        </div>
                        <div v-else>Não existe empresa cadastrada</div>
                      </v-sheet>
                    </v-col>
                  </v-row>
                </v-container>
              </v-form>
              <v-dialog
                v-model="engagedBrideUploadPhoto"
                transition="dialog-bottom-transition"
                max-width="600"
                persistent
              >
                <v-card>
                  <v-toolbar color="primary" dark>
                    Upload de foto da noiva
                  </v-toolbar>
                  <v-card-text>
                    <v-file-input
                      v-model="currentFileBride"
                      label="Foto"
                      chips
                      @change="
                        readEngagedPhoto(currentFileBride, 'engagedBrideBlob')
                      "
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
                  <v-toolbar color="primary" dark>
                    Upload de foto do noivo
                  </v-toolbar>
                  <v-card-text>
                    <v-file-input
                      v-model="currentFileGroom"
                      label="Foto"
                      chips
                      @change="
                        readEngagedPhoto(currentFileGroom, 'engagedGroomBlob')
                      "
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
                    Cadastrar demais serviços do casamento
                  </v-toolbar>
                  <v-form>
                    <v-container>
                      <v-row>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="weddingServiceCompanyName"
                            label="Nome da empresa"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="weddingServicePhone"
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
                            label="Nome de contato"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <v-btn
                            color="grey darken-4"
                            class="mr-3"
                            dark
                            @click="saveWeddingService()"
                            >Salvar</v-btn
                          >
                          <v-btn
                            color="secondary"
                            @click="closeFormWeddingServices()"
                            >Fechar</v-btn
                          >
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-form>
                </v-card>
              </v-dialog>
            </v-card-text>
            <v-card-text v-show="showTabInscribe == 'committe'">
              <v-skeleton-loader
                v-if="loadingCommitteFields"
                type="text, button"
              ></v-skeleton-loader>
              <v-form
                v-show="!loadingCommitteFields"
                ref="formGraduationCommitte"
              >
                <v-row>
                  <v-col>
                    <v-text-field
                      v-model="graduationCommitteName"
                      label="Membro da comissão"
                      :rules="graduationCommitteNameRules"
                      required
                    >
                    </v-text-field>
                    <v-btn
                      color="grey darken-4"
                      class="white--text"
                      depressed
                      @click="addMemberGraduationCommitte()"
                    >
                      <v-icon>mdi-plus</v-icon>
                      Acrescentar
                    </v-btn>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <v-chip
                      v-for="(member, i) in graduationCommitteMember"
                      :key="i"
                      class="ma-1"
                      close
                      @click:close="removeMemberGraduationCommitte(i, 1)"
                    >
                      {{ member }}
                    </v-chip>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col>
                    <v-alert v-show="alert" type="info" dismissible
                      >{{ alertMsg }}
                    </v-alert>
                  </v-col>
                </v-row>
              </v-form>
            </v-card-text>
            <v-card-actions v-show="showTabInscribe == 'inscribe'">
              <v-spacer></v-spacer>
              <v-btn
                depressed
                dark
                large
                color="grey darken-4"
                @click="saveInscribe()"
              >
                <v-icon>mdi-content-save</v-icon> Salvar
              </v-btn>
            </v-card-actions>
            <v-card-actions v-show="showTabInscribe == 'events'">
              <v-spacer></v-spacer>
              <v-btn
                depressed
                dark
                large
                color="grey darken-4"
                @click="saveEvent()"
              >
                <v-icon>mdi-content-save</v-icon> Salvar
              </v-btn>
            </v-card-actions>
            <v-card-actions v-show="showTabInscribe == 'engaged'">
              <v-spacer></v-spacer>
              <v-btn
                depressed
                dark
                large
                color="grey darken-4"
                @click="saveEngaged()"
              >
                <v-icon>mdi-content-save</v-icon> Salvar
              </v-btn>
            </v-card-actions>
            <v-card-actions v-show="showTabInscribe == 'committe'">
              <v-spacer></v-spacer>
              <v-btn
                depressed
                dark
                large
                color="grey darken-4"
                @click="saveCommitte()"
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

function getAge(date) {
  var today = new Date();
  var birthdate = new Date(convertToMMDDYYYY(date.split("/")));
  var year = today.getFullYear() - birthdate.getFullYear();
  var month = today.getMonth() - birthdate.getMonth();
  if (month < 0 || (month === 0 && today.getDate() < birthdate.getDate())) {
    year--;
  }
  return year;
}

function convertToMMDDYYYY(date) {
  return date[1] + "-" + date[0] + "-" + date[2];
}

export default {
  data: () => ({
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
    loadingEventFields: false,
    eventEmpty: true,
    eventID: "",
    eventName: "",
    eventDate: "",
    eventDateCountDown: "",
    eventTime: "",
    countDownNow: new Date(),
    countDownTime: undefined,
    eventAddress: {
      street: "",
      number: "",
      complement: "",
      neighborhood: "",
      city: "",
      zipcode: "",
      state: "",
      country: "",
    },
    pickDateEvent: false,
    pickTimeEvent: false,
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
    engagedBridePhoneRules: [
      (v) => !!v || "O campo TELEFONE DA NOIVA é requerido",
    ],
    engagedBrideMobile: "",
    engagedBrideMobileRules: [
      (v) => !!v || "O campo CELULAR DA NOIVA é requerido",
    ],
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
    engagedGroomPhoneRules: [
      (v) => !!v || "O campo TELEFONE DO NOIVO é requerido",
    ],
    engagedGroomMobile: "",
    engagedGroomMobileRules: [
      (v) => !!v || "O campo CELULAR DO NOIVO é requerido",
    ],
    engagedGroomCpf: "",
    engagedGroomRg: "",
    engagedGroomBirthdate: "",
    engagedGroomEmail: "",
    engagedGroomResponsibleFor: false,
    engagedBrideUploadPhoto: false,
    engagedGroomUploadPhoto: false,
    loadingCommitteFields: false,
    committeID: "",
    graduationCommitteName: "",
    graduationCommitteNameRules: [(v) => !!v || "Por favor digite um nome"],
    graduationCommitteMember: [],
    loadingSelectFields: false,
    formWeddingServices: false,
    loadingWeddingServices: false,
    weddingServiceID: "",
    weddingServiceCompanyName: "",
    weddingServiceAddress: "",
    weddingServicePhone: "",
    weddingServiceContactName: "",
    weddingServices: [],
  }),

  methods: {
    ...mapActions(["setUserNow"]),
    activeTabInscribe: function (tab) {
      this.showTabInscribe = tab;
      this.resetAllVars();
      this.getInscribe();
      switch (tab) {
        case "events":
          this.tabInscribeTitle = "Dados do evento";
          this.$refs.formEvents.reset();
          this.getEvent();
          break;
        case "inscribe":
          this.tabInscribeTitle = "Dados do cadastro";
          this.getServices();
          this.getFormations();
          break;
        case "engaged":
          this.tabInscribeTitle = "Dados dos noivos";
          this.$refs.formEngaged.reset();
          this.getEngaged();
          break;
        case "committe":
          this.tabInscribeTitle = "Comissão de formatura";
          this.getCommitte();
          break;
      }
    },
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
      (this.engagedGroomResponsibleFor = false),
        (this.graduationCommitteName = "");
      this.graduationCommitteMember = [];
    },
    maskTel: function (phone) {
      if (!!phone) {
        return phone.length == 15 ? this.maskMobile : this.maskPhone;
      } else {
        return this.maskMobile;
      }
    },
    is18: function (date){
      if(getAge(date) < 18){
        this.$swal('Responsável precisa ter mais que 18 anos')
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
          .post(process.env.VUE_APP_URL + "uploadPhoto", data, {
            onUploadProgress: (event) => {
              const totalLength = event.lengthComputable
                ? event.total
                : event.target.getResponseHeader("content-length") ||
                  event.target.getResponseHeader(
                    "x-decompressed-content-length"
                  );
              console.log("onUploadProgress", totalLength);
              if (totalLength !== null) {
                this.progressUpload = Math.round(
                  (event.loaded * 100) / totalLength
                );
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
    addMemberGraduationCommitte: function () {
      if (this.$refs.formGraduationCommitte.validate()) {
        this.graduationCommitteMember.push(this.graduationCommitteName);
        this.$refs.formGraduationCommitte.reset();
        this.alert = false;
        this.alertMsg = "";
      }
    },
    removeMemberGraduationCommitte: function (i, n) {
      this.graduationCommitteMember.splice(i, n);
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
    getServices: function () {
      this.loadingAgreementService = true;
      axios.get(process.env.VUE_APP_URL + "getServices").then((response) => {
        this.services = response.data;
        this.loadingAgreementService = false;
      });
    },
    getFormations: function () {
      this.loadingAgreementFormation = true;
      axios.get(process.env.VUE_APP_URL + "getFormation").then((response) => {
        this.formation = response.data;
        this.loadingAgreementFormation = false;
      });
    },
    getInscribe: function () {
      this.loadingInscribeFields = true;
      axios
        .get(
          process.env.VUE_APP_URL + "getInscribeCustomers/" + this.userNow.id
        )
        .then((response) => {
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
        });
    },
    getEvent: function () {
      this.loadingEventFields = true;
      this.loadingAgreementEvent = true;
      axios
        .get(process.env.VUE_APP_URL + "getEventCustomers/" + this.inscribeID)
        .then((response) => {
          const resp = response.data;
          if (resp) {
            this.eventID = resp.idevent;
            this.eventName = resp.name;
            this.eventDate = resp.date;
            this.eventDateCountDown = new Date(resp.date);
            this.eventTime = resp.time;
            this.eventAddress = resp.address;
            this.crud = "u";
            this.eventEmpty = false;
            this.countDownTime = this.eventDateCountDown - this.countDownNow;
          } else {
            this.crud = "c";
          }
          this.loadingEventFields = false;
          this.loadingAgreementEvent = false;
        });
    },
    getEngaged: async function () {
      this.loadingEngagedFields = true;
      const response = await axios.get(
        process.env.VUE_APP_URL + "getEngagedCustomers/" + this.inscribeID
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
        this.getWeddingServices(resp.idengaged);
      } else {
        this.crud = "c";
        this.selectEngaged = false;
        console.log(this.selectEngaged);
      }
      this.loadingEngagedFields = false;
    },
    getCommitte: function () {
      this.loadingCommitteFields = true;
      axios
        .get(
          process.env.VUE_APP_URL + "getCommitteCustomers/" + this.inscribeID
        )
        .then((response) => {
          const resp = response.data;
          if (resp) {
            this.crud = "u";
            this.committeID = resp.idgraduation_committe;
            this.graduationCommitteName = "";
            this.graduationCommitteMember = resp.committe_name;
          } else {
            this.crud = "c";
          }
          this.loadingCommitteFields = false;
        });
    },
    getWeddingServices: async function (id) {
      this.loadingWeddingServices = true;
      const response = await axios.get(
        process.env.VUE_APP_URL + "getWeddingServices/" + id
      );
      this.weddingServices = response.data;
      this.loadingWeddingServices = false;
    },
    saveInscribe: function () {
      let data = new FormData();
      data.append("accountable", this.inscribeAccountable);
      data.append("phone", this.inscribePhone);
      data.append("mobile", this.inscribeMobile);
      data.append("address", JSON.stringify(this.inscribeAddress));
      data.append("cpf", this.inscribeCpf);
      data.append("rg", this.inscribeRg);
      data.append("idservice", this.inscribeService.idservice);
      data.append("idformation", this.inscribeFormation.idformation);
      axios(
        process.env.VUE_APP_URL + "updateInscribeCustomers/" + this.inscribeID,
        {
          method: "POST",
          data: data,
        }
      ).then((response) => {
        this.$swal(response.data.msg, "", response.data.icon);
        this.getInscribe();
      });
    },
    saveEvent: function () {
      let data = new FormData();
      data.append("eventname", this.eventName);
      data.append("eventdate", this.eventDate);
      data.append("eventtime", this.eventTime);
      data.append("eventaddress", JSON.stringify(this.eventAddress));
      data.append("idinscribe", this.inscribeID);
      if (this.crud == "c") {
        axios(process.env.VUE_APP_URL + "createEventCustomers", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          this.getEvent();
        });
      } else if (this.crud == "u") {
        axios(
          process.env.VUE_APP_URL + "updateEventCustomers/" + this.eventID,
          {
            method: "POST",
            data: data,
          }
        ).then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          this.getEvent();
        });
      }
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
      data.append(
        "groom_birthdate",
        FormataStringData(this.engagedGroomBirthdate)
      );
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
      data.append(
        "bride_birthdate",
        FormataStringData(this.engagedBrideBirthdate)
      );
      data.append("bride_email", this.engagedBrideEmail);
      data.append("bride_responsible_for", this.engagedBrideResponsibleFor);
      data.append("idinscribe", this.inscribeID);

      if (this.crud == "c") {
        axios(process.env.VUE_APP_URL + "createEngagedCustomers", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          this.getEngaged();
        });
      } else if (this.crud == "u") {
        axios(
          process.env.VUE_APP_URL + "updateEngagedCustomers/" + this.engagedID,
          {
            method: "POST",
            data: data,
          }
        ).then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          this.getEngaged();
        });
      }
    },
    saveCommitte: function () {
      let data = new FormData();
      data.append(
        "committe_name",
        JSON.stringify(this.graduationCommitteMember)
      );
      data.append("inscribe_idinscribe", this.inscribeID);
      if (this.crud == "c") {
        axios(process.env.VUE_APP_URL + "createCommitteCustomers", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          this.getCommitte();
        });
      } else if (this.crud == "u") {
        axios(
          process.env.VUE_APP_URL +
            "updateCommitteCustomers/" +
            this.committeID,
          {
            method: "POST",
            data: data,
          }
        ).then((response) => {
          this.$swal(response.data.msg, "", response.data.icon);
          this.getCommitte();
        });
      }
    },
    saveWeddingService: function () {
      let data = new FormData();
      data.append("companyname", this.weddingServiceCompanyName);
      data.append("address", this.weddingServiceAddress);
      data.append("phone", this.weddingServicePhone);
      data.append("contactname", this.weddingServiceContactName);
      data.append("engaged_idengaged", this.engagedID);
      if (this.crud == "c") {
        axios(process.env.VUE_APP_URL + "createWeddingServices", {
          method: "POST",
          data: data,
        }).then((response) => {
          this.closeFormWeddingServices();
          this.$swal(response.data.msg, "", response.data.icon);
          this.getWeddingServices(this.engagedID);
        });
      } else if (this.crud == "u") {
        axios(
          process.env.VUE_APP_URL +
            "updateWeddingServices/" +
            this.weddingServiceID,
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
    },
    deleteWeddingService: function (id) {
      this.loading = true;
      axios
        .get(process.env.VUE_APP_URL + "deleteWeddingService/" + id)
        .then((response) => {
          this.loading = false;
          this.$swal(response.data.msg, "", response.data.icon);
          this.getWeddingServices(this.engagedID);
        });
    },
  },

  computed: {
    ...mapGetters(["userNow"]),
  },

  created: function () {
    if (this.$session.exists()) {
      this.setUserNow(this.$session.get("userData"));
    }
    this.getInscribe();
    this.getServices();
    this.getFormations();
  },
};
</script>