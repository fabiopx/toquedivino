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
              <v-sheet
                outlined
                rounded
                class="d-flex justify-space-around align-center"
              >
                <v-img
                  v-if="!access.isAgreement"
                  max-width="200"
                  :src="require('../assets/undraw_contract_re_ves9.svg')"
                ></v-img>
                <div v-if="access.isBudget && !access.isAgreement">
                  <h1 class="red--text darken-4 h2">
                    Você ainda não gerou seu contrato.
                  </h1>
                  <p
                    v-if="access.isBudget && budget.status == 1"
                    class="subtitle-1 mt-3"
                  >
                    Existe um orçamento ativo na sua conta, você já pode gerar
                    seu contrato.
                  </p>
                </div>
                <div v-if="!access.isAgreement && !access.isBudget">
                  <h1 class="red--text darken-4 h2">Faça um orçamento</h1>
                  <p class="subtitle-1">
                    Não há nenhum orçamento realizado em sua conta. Faça um
                    orçamento:
                  </p>
                </div>
                <div v-if="access.isAgreement">
                  <v-alert
                    border="top"
                    color="red darken-4"
                    v-if="!engaged"
                    dark
                    dismissible
                    >Os dados dos noivos não foram cadastrados.</v-alert
                  >
                  <p class="subtitle-1 pa-5 grey darken-4 white--text">
                    Leia atentamente o contrato abaixo. Estando de acordo com os
                    termos do contrato, assine eletronicamente usando a senha
                    enviada para o e-mail.
                  </p>

                  <!-- Contrato -->
                  <div class="overflow-auto pa-8" style="height: 500px">
                    <p style="text-align: center">
                      <span style="font-size: 22px"
                        ><strong
                          >CONTRATO DE RESPONSABILIDADE E PRESTAÇÃO DE SERVIÇOS
                          MUSICAIS</strong
                        ></span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >O presente contrato de responsabilidade e prestação de
                        serviços musicais tem como objetivo formalizar acordo
                        entre as partes estabelecendo diretrizes para
                        compromisso formal de prestação de serviços, de um lado,
                        a empresa
                        <em
                          >TOQUE DIVINO ORQUESTRA, inscrita no CNPJ de n&ordm;
                          04.504.978/0001-41, </em
                        >estabelecida comercialmente Rua Doze de novembro, 804 -
                        Americana - SP ,<em> </em>denominada CONTRATADA e do
                        outro, denominado CONTRATANTE, conforme dados
                        preenchidos abaixo:</span
                      >
                    </p>

                    <hr />
                    <h2 class="mt-3 mb-3">Responsável pelo Contrato</h2>

                    <p>Nome: {{ inscribe.accountable }}</p>
                    <p>CPF: {{ inscribe.cpf }}</p>
                    <p>RG: {{ inscribe.rg }}</p>
                    <p>Telefone: {{ inscribe.pPhone }}</p>
                    <p>Celular: {{ inscribe.mobile }}</p>
                    <div v-if="engaged" v-show="engaged.selectEngaged">
                      <hr />
                      <h2 class="mb-3 mt-3">Dados dos Noivos</h2>
                      <p>
                        <span style="font-size: 14px"
                          >Noiva: {{ engaged.bride_name }}</span
                        >
                      </p>

                      <p>
                        <span style="font-size: 14px"
                          >RG: {{ engaged.bride_rg }}&nbsp;&nbsp; &nbsp;
                          &nbsp;CPF: {{ engaged.bride_cpf }}&nbsp; &nbsp; &nbsp;
                          Data Nascimento:
                          {{
                            $moment(engaged.bride_birthdate).format(
                              "DD/MM/YYYY"
                            )
                          }}</span
                        >
                      </p>

                      <p>
                        <span style="font-size: 14px"
                          >Endere&ccedil;o: {{ engaged.bride_address.street }},
                          {{ engaged.bride_address.number }}
                          {{ engaged.bride_address.complement }},
                          {{ engaged.bride_address.neighborhood }},
                          {{ engaged.bride_address.city }},
                          {{ engaged.bride_address.state }},
                          {{ engaged.bride_address.country }}</span
                        >
                      </p>

                      <p>
                        <span style="font-size: 14px"
                          >Tel: {{ engaged.bride_mobile }}&nbsp; &nbsp; &nbsp;
                          &nbsp;e-mail: {{ engaged.bride_email }}</span
                        >
                      </p>

                      <p>
                        <span style="font-size: 14px"
                          >Noivo: {{ engaged.groom_name }}</span
                        >
                      </p>

                      <p>
                        <span style="font-size: 14px"
                          >RG: {{ engaged.groom_rg }}&nbsp; &nbsp; &nbsp; CPF:
                          {{ engaged.groom_cpf }}&nbsp; &nbsp; &nbsp; Data
                          Nascimento:
                          {{
                            $moment(engaged.groom_birthdate).format(
                              "DD/MM/YYYY"
                            )
                          }}</span
                        >
                      </p>

                      <p>
                        <span style="font-size: 14px"
                          >Endere&ccedil;o: {{ engaged.groom_address.street }},
                          {{ engaged.groom_address.number }}
                          {{ engaged.groom_address.complement }},
                          {{ engaged.groom_address.neighborhood }},
                          {{ engaged.groom_address.city }},
                          {{ engaged.groom_address.state }},
                          {{ engaged.groom_address.country }}</span
                        >
                      </p>

                      <p>
                        <span style="font-size: 14px"
                          >Tel: {{ engaged.groom_mobile }}&nbsp; &nbsp; &nbsp;
                          &nbsp;e-mail: {{ engaged.groom_email }}</span
                        >
                      </p>
                    </div>
                    <hr class="mt-3 mb-3" />
                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >A CONTRATADA e CONTRATANTE estabelecem em comum acordo
                        na presta&ccedil;&atilde;o e execu&ccedil;&atilde;o de
                        servi&ccedil;os musicais, conforme as clausulas que
                        seguem abaixo:</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Clausula 1&ordf; - O valor total dos servi&ccedil;os a
                        serem prestados pela CONTRATADA no presente contrato
                        &eacute; de R$&nbsp;{{
                          contract.value_total.toString().replace(".", ",")
                        }}&nbsp;({{ contract.value_total_extenso }}) a serem
                        pagos da seguinte forma:
                      </span>
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Par&aacute;grafo 1&ordm; - &Eacute; de
                        obriga&ccedil;&atilde;o do CONTRATANTE efetuar o
                        pagamento de no m&iacute;nimo R$
                        {{ contract.down_payment.replace(".", ",") }}&nbsp;({{
                          contract.down_payment_extenso
                        }}) do valor total na data de&nbsp;{{
                          $moment(contract.down_payment_date).format(
                            "DD/MM/YYYY"
                          )
                        }}&nbsp;e o restante, podendo efetuar o pagamento em
                        at&eacute; 15 (quinze) dias &uacute;teis antes do evento
                        (clausula 2&ordf;), sob pena de cancelamento do contrato
                        motivado pelo contratante conforme par&aacute;grafo
                        4&ordm;.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Par&aacute;grafo 2&ordm; - O pagamento poder&aacute;
                        ser feito em esp&eacute;cie &nbsp;ou dep&oacute;sito
                        banc&aacute;rio (nesse &uacute;ltimo caso, o pagamento
                        ser&aacute; considerado somente ap&oacute;s
                        confirma&ccedil;&atilde;o banc&aacute;ria).</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Contas Banc&aacute;rias Dispon&iacute;veis no link:
                        www.toquedivino.com/conta , enviar comprovante no email
                        financeiro@toquedivino.com, informando data e
                        hor&aacute;rio do evento, para que ser providenciado a
                        baixa no sistema.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Par&aacute;grafo 3&ordm; - Na eventualidade de atrasos
                        de pagamentos, de cheques sem provis&atilde;o de fundos
                        ou qualquer outra forma de inadimpl&ecirc;ncia da
                        Clausula 1&ordf; do presente contrato, os d&eacute;bitos
                        arcados pelo CONTRATANTE sofrer&atilde;o multa de 2%
                        (dois por cento) e juros de 1% (um por cento) ao
                        dia.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Par&aacute;grafo 4&ordm; - Na hip&oacute;tese de
                        cancelamento do contrato, por qualquer motivo, sendo a
                        parte CONTRATANTE motivadora da
                        dissolu&ccedil;&atilde;o, o mesmo n&atilde;o ter&aacute;
                        direito &agrave; devolu&ccedil;&atilde;o do valor pago.
                        Caso ainda o CONTRATANTE n&atilde;o tenha pagado nenhum
                        valor contratado e se for dele o motivo da
                        dissolu&ccedil;&atilde;o do contrato, o mesmo
                        pagar&aacute; a titulo de multa por quebra de contrato
                        de 30% (trinta por cento) do valor contido na clausula
                        1&ordf; e desde que, avisado com pelo menos 60 dias de
                        anteced&ecirc;ncia.
                      </span>
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Par&aacute;grafo 5&ordm; - O n&atilde;o pagamento do
                        valor total deste contrato at&eacute; o prazo
                        estabelecido na clausula 1&ordf;, par&aacute;grafo
                        1&ordm; deste documento desobriga automaticamente a
                        CONTRATADA da execu&ccedil;&atilde;o dos servi&ccedil;os
                        aqui descritos ao CONTRATANTE, salvo se, este, quitar o
                        valor restante exclusivamente em esp&eacute;cie e/ou
                        concordar com a condi&ccedil;&atilde;o de uma
                        cau&ccedil;&atilde;o da pra&ccedil;a no valor total
                        deste documento e que ser&aacute; devolvido ap&oacute;s
                        quita&ccedil;&atilde;o do saldo devedor acrescido de
                        multa e juros conforme par&aacute;grafo 3&ordm; acima. O
                        descumprimento do prazo pelo CONTRATANTE e a n&atilde;o
                        execu&ccedil;&atilde;o do trabalho por esse motivo ou
                        qualquer outro motivado por este, n&atilde;o diminui nem
                        exclui o CONTRATANTE da quita&ccedil;&atilde;o deste
                        documento em seu valor total dentro dos prazos
                        estabelecidos.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Clausula 2&ordf; - Fica a CONTRATADA obrigada,
                        ap&oacute;s a obriga&ccedil;&atilde;o do CONTRATANTE
                        contida na clausula 1&ordf;, a prestar servi&ccedil;os
                        de execu&ccedil;&atilde;o de m&uacute;sicas para a
                        cerim&ocirc;nia de casamento do CONTRATANTE que,
                        ser&aacute; realizado na data, hor&aacute;rio e local
                        abaixo especificado.&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        &nbsp; &nbsp; &nbsp;</span
                      >
                    </p>

                    <p>
                      <span style="font-size: 14px">lista_locais_formacao</span>
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Par&aacute;grafo &Uacute;nico &ndash; &Eacute; de
                        responsabilidade da CONTRATADA levar seus instrumentos
                        musicais e equipamentos de som para o evento contratado
                        (caso a igreja, institui&ccedil;&atilde;o ou local do
                        referido evento n&atilde;o possuam) inerentes &agrave;
                        boa execu&ccedil;&atilde;o do trabalho. Os equipamentos
                        e instrumentos da CONTRATADA s&atilde;o
                        espec&iacute;ficos para a execu&ccedil;&atilde;o do
                        trabalho desta, n&atilde;o sendo dela a
                        obriga&ccedil;&atilde;o de sonorizar qualquer outro tipo
                        de ambiente ou situa&ccedil;&atilde;o e ainda,
                        terceiros.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Clausula 3&ordf; - O CONTRATANTE deve com
                        anteced&ecirc;ncia escolher o repert&oacute;rio de
                        musicas para seu casamento de acordo com cada momento da
                        cerim&ocirc;nia, sem limite de m&uacute;sicas.</span
                      >
                    </p>

                    <p>
                      <span style="font-size: 14px"
                        >Par&aacute;grafo 1&ordm; - A CONTRATADA
                        limitar-se-&aacute; a auxiliar e orientar as escolhas de
                        musicas para a cerim&ocirc;nia do CONTRATANTE, sem,
                        por&eacute;m, influenciar na decis&atilde;o final.
                        At&eacute; uma musica que n&atilde;o tenha no
                        repert&oacute;rio da CONTRATADA &eacute; inclu&iacute;da
                        sem custo adicional.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Par&aacute;grafo 2&ordm; - O repert&oacute;rio
                        escolhido pelo CONTRATANTE poder&aacute; ser alterado em
                        at&eacute; 60 (sessenta) dias antes da cerim&ocirc;nia
                        contida na clausula 1&ordf;, n&atilde;o podendo mais
                        faz&ecirc;-lo ap&oacute;s esse prazo, salvo se a
                        CONTRATADA julgar poss&iacute;vel.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Clausula 4&ordf; - É de obrigação da CONTRATADA, após a
                        assinatura deste contrato, estar no local, data e
                        horário estabelecido na Clausula 2&ordf;, para a
                        realização do trabalho.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Parágrafo 1&ordm; - Os serviços não serão realizados
                        pela CONTRATADA na eventualidade de ocorrer falta de
                        energia elétrica no evento, falta de segurança para a
                        equipe CONTRATADA ou até casos extremos (acidentes
                        graves ou até morte de membros da equipe), não cabendo a
                        parte CONTRATANTE pedido de indenização.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Parágrafo 2&ordm; - Em caso de ocorrência ou nítida
                        ameaça de chuvas, temporais ou qualquer outra espécie de
                        adversidade climática que possa danificar instrumentos e
                        equipamentos, prejudicar a saúde dos integrantes da
                        equipe, sendo o local ambiente aberto ou exposto, a
                        CONTRATADA se reserva o direito de desligar e desmontar
                        imediatamente todo o equipamento e/ou não montar e nem
                        ligar, caso ainda não tenha sido feito, cancelando o
                        trabalho se o CONTRATANTE não providenciar local seguro
                        para a execução do trabalho, não dando direito ao
                        CONTRATANTE pedido de indenização ou qualquer estorno de
                        valor pago.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Parágrafo 3&ordm; - O respectivo serviço musical
                        contratado, terá sua duração de 1h (uma hora) a partir
                        do horário convencionado na Clausula 2&ordf;, com
                        tolerância a mais de 15 (quinze minutos). Qualquer tempo
                        a mais que a CONTRATADA permanecer no evento devido a
                        atrasos, será cobrado um adicional com mesmo valor deste
                        documento contido na cláusula 1&ordf;, cabendo à
                        CONTRATADA e não ao CONTRATANTE a decisão de permanecer
                        ou não no evento após o tempo determinado.</span
                      >
                    </p>

                    <p>
                      <span style="font-size: 14px"
                        >Parágrafo 4&ordm; - Na casualidade de ocorrer atraso
                        para o inicio da cerimônia, serão tolerados pela
                        CONTRATADA atrasos de no máximo 20 (vinte minutos) após
                        o horário acima declarado, não havendo responsabilidade
                        por parte da CONTRATADA em continuar no local após esse
                        período.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Clausula 5&ordf; - A CONTRATADA não se responsabiliza
                        por ausências, ou ainda, por eventuais danos materiais
                        ou morais causados por terceiros e/ou músicos terceiros
                        contratados pelo CONTRATANTE para o mesmo evento.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Clausula 6&ordf; - Os danos causados nos
                        equipamentos/instrumentos de som da CONTRATADA
                        ocasionados pelo CONTRATANTE, seus convidados ou ainda,
                        terceiros contratados pelo CONTRATANTE para o evento,
                        serão de inteira responsabilidade do CONTRATANTE, sob
                        pena de pagamentos em Dobro dos Danos ocasionados, caso
                        a CONTRATADA tenha que ingressar com Ação
                        Judicial.</span
                      >
                    </p>

                    <p style="text-align: justify">
                      <span style="font-size: 14px"
                        >Clausula 7&ordf; - O serviço de assistente que a
                        CONTRATADA oferece é exclusivo para auxilio da equipe de
                        músicos, não se obrigando a responsabilizar por demais
                        encargos.</span
                      >
                    </p>

                    <p>
                      <span style="font-size: 14px"
                        >Clausula 8&ordf; - Fica eleito o Foro da cidade de
                        Americana para soluções de quaisquer questões
                        decorrentes deste termo, com expressa renúncia a
                        qualquer outro, por mais privilegiado que seja.</span
                      >
                    </p>

                    <p>
                      <span style="font-size: 14px"
                        >E por estarem de acordo com as condições estabelecidas,
                        as partes, CONTRATANTE e CONTRATADA assinam este
                        instrumento em 02 (duas) vias de igual teor e
                        eficácia.</span
                      >
                    </p>
                    <hr />
                    <v-row>
                      <v-col class="text-center">
                        <div v-for="sign in signatures" :key="sign.idsignature">
                          <p
                            class="text-subtitle-1 mt-2"
                            :class="sign.sign == 0 ? signNotInUse : null"
                            v-if="sign.type == 1"
                          >
                            {{ sign.name }}
                          </p>
                          <p
                            class="text-subtitle-2 mt-n6"
                            :class="sign.sign == 0 ? signNotInUse : null"
                            v-if="sign.type == 1"
                          >
                            {{ selSignatureType(sign.type) }}
                          </p>
                        </div>
                      </v-col>
                      <v-col class="text-center">
                        <div v-for="sign in signatures" :key="sign.idsignature">
                          <p
                            class="text-subtitle-1 mt-2"
                            :class="sign.sign == 0 ? signNotInUse : null"
                            v-if="sign.type == 2 || sign.type == 3"
                          >
                            <v-icon v-if="sign.sign == 1">mdi-check</v-icon
                            >{{ sign.name }}
                          </p>
                          <p
                            class="text-subtitle-2 mt-n6"
                            :class="sign.sign == 0 ? signNotInUse : null"
                            v-if="sign.type == 2 || sign.type == 3"
                          >
                            {{ selSignatureType(sign.type) }}
                          </p>
                        </div>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="2" lg="2">
                        <qr-code text="https://viadelivery.com.br" size="100"></qr-code>
                      </v-col>
                      <v-col cols="12" md="8" lg="8">
                        <p>Contrato assinado eletronicamente por {{ inscribe.accountable }}</p>
                      </v-col>
                    </v-row>
                  </div>
                  <!-- Fim Contrato -->
                </div>
              </v-sheet>
              <v-sheet v-if="!access.isAgreement" class="mt-3">
                <v-btn
                  color="red darken-4"
                  class="pa-8 mr-3"
                  dark
                  @click="$router.push('/customer/budget')"
                  ><v-icon class="mr-3">mdi-file-document-edit</v-icon>Acessar
                  orçamento</v-btn
                >
                <v-btn
                  v-if="access.isBudget && budget.status == 1"
                  color="red darken-4"
                  class="pa-8"
                  dark
                  @click="generateContract()"
                  ><v-icon class="mr-3">mdi-text-box-plus</v-icon>Gerar
                  contrato</v-btn
                >
              </v-sheet>
            </v-card-text>
            <v-card-actions v-if="access.isAgreement">
              <v-spacer></v-spacer>
              <v-menu rounded="lg" offset-x color="grey darken-4" dark>
                <template v-slot:activator="{ attrs, on }">
                  <v-btn
                    class="mr-3"
                    depressed
                    dark
                    color="red darken-4 pa-5"
                    v-bind="attrs"
                    v-on="on"
                    ><v-icon class="ma-2">mdi-file-sign</v-icon></v-btn
                  >
                </template>
                <v-list>
                  <v-list-item
                    v-for="signature in contractors"
                    :key="signature.idsignature"
                    link
                    @click="signaturePassword(signature)"
                  >
                    <v-list-item-title v-show="signature.type == 1">{{
                      signature.name
                    }}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>

              <v-btn class="mr-3" depressed dark color="red darken-4 pa-5"
                ><v-icon class="ma-2">mdi-file-pdf-box</v-icon></v-btn
              >
            </v-card-actions>
          </v-card>
          <v-overlay :value="loading"
            ><v-progress-circular indeterminate></v-progress-circular
          ></v-overlay>
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
    loading: false,
    inscribe: {},
    engaged: { bride_address: {}, groom_address: {} },
    selectEngaged: true,
    contract: { value_total: "", down_payment: "" },
    signatures: [],
    contractors: [],
    signatureType: "",
    signNotInUse: "red--text darken-4",
    ip: { ip: "", latitude: "", longitude: "" },
  }),
  methods: {
    ...mapActions([
      "setInscribeID",
      "setUserNow",
      "setBudgetActive",
      "verifyIsAgreement",
    ]),
    getIP: async function () {
      const resp = await axios.get("https://ipapi.co/json/");
      this.ip = resp.data;
    },
    generateCode: function () {
      var chars =
        "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJLMNOPQRSTUVWXYZ";
      var passwordLength = 16;
      var password = "";

      for (var i = 0; i < passwordLength; i++) {
        var randomNumber = Math.floor(Math.random() * chars.length);
        password += chars.substring(randomNumber, randomNumber + 1);
      }

      return password;
    },
    generateContract: async function () {
      this.loading = true;
      const response = await axios.get(
        this.apiURL + "/agreements/createCustomer/" + this.inscribeID
      );
      // this.loading = false;
      // this.$swal(response.data.msg, "", response.data.icon);
      // this.loading = true;
      await this.verifyIsAgreement();
      await this.getEngaged();
      await this.getAgreement();
      await this.getSignature();
      this.loading = false;
    },
    getInscribe: async function () {
      const response = await axios.get(
        this.apiURL + "/inscribes/getCustomers/" + this.userNow.id
      );
      this.inscribe = response.data;
    },
    getAgreement: async function () {
      const response = await axios.get(
        this.apiURL + "/agreements/getCustomer/" + this.inscribeID
      );
      if (response.data) {
        this.contract = response.data;
        this.contract.down_payment_extenso = this.$extenso(
          response.data.down_payment.replace(".", ","),
          { number: { decimal: "formal" }, mode: "currency" }
        );
        this.contract.value_total_extenso = this.$extenso(
          response.data.value_total.toString().replace(".", ","),
          { number: { decimal: "formal" }, mode: "currency" }
        );
      }
    },
    getEngaged: async function () {
      const response = await axios.get(
        this.apiURL + "/engagedes/getCustomers/" + this.inscribeID
      );
      this.engaged = response.data;
    },
    getSignature: async function () {
      const response = await axios.get(
        this.apiURL + "/signatures/getSignaturesContract/" + this.inscribeID
      );
      this.signatures = response.data;
      this.contractors = this.signatures.filter(
        (item) => item.type == 1 && item.sign != 1
      );
    },
    selSignatureType: function (type) {
      if (type == 1) return "Contratante";
      if (type == 2) return "Contratado";
      if (type == 3) return "Testemunha";
    },
    signaturePassword: async function (sign) {
      const { value: password } = await this.$swal({
        title: "Assinatura do contrato ",
        icon: "question",
        input: "password",
        inputLabel: "Assinatura eletrônica de " + sign.name,
        inputPlaceholder: "Entre com sua senha",
        inputAtrributes: {
          maxLength: 10,
          autocapitalize: "off",
          autocorrect: "off",
        },
        confirmButtonText: "Assinar",
        showCancelButton: true,
      });
      if (password) {
        const data = new FormData();
        data.append("idsignature", sign.idsignature);
        data.append("idinscribe", this.inscribeID);
        data.append("idaccount", sign.account_idaccount);
        data.append("date", this.$moment().format("YYYY-MM-DD HH:mm:ss"));
        data.append("ip", this.ip.ip);
        data.append("geolocation", this.ip.latitude + ", " + this.ip.longitude);
        data.append("hash", this.generateCode());
        data.append("password", password);
        const response = await axios(
          this.apiURL + "/signatures/signWithPassword",
          {
            method: "POST",
            data: data,
          }
        );
        this.$swal(response.data.msg, "", response.data.icon);
        this.getSignature();
      }
    },
  },
  computed: {
    ...mapGetters(["inscribeID", "userNow", "access", "budget"]),
  },
  created: async function () {
    await this.setBudgetActive();
    await this.verifyIsAgreement();
    this.loading = true;
    await this.getInscribe();
    await this.getEngaged();
    await this.getAgreement();
    await this.getSignature();
    await this.getIP();
    this.loading = false;
  },
};
</script>
<style lang="css" scoped>
.gf-fuggles {
  font-family: "Fuggles", cursive;
}
</style>