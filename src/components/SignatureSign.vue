<template>
  <div>
    <v-sheet
      v-if="loaded"
      elevation="1"
      rounded="lg"
      color="white"
      class="pa-5 ma-3"
    >
      <h1>Contrato {{ $route.params.agreement }}</h1>
      <h3>Assinante: {{ contract.signature.name }}</h3>
      <p>
        Leia atentamente o contrato abaixo. Estando de acordo com os termos do
        contrato, assine eletronicamente usando a senha enviada para o e-mail
        cadastrado.
      </p>
      <v-divider></v-divider>
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
            >O presente contrato de responsabilidade e prestação de serviços
            musicais tem como objetivo formalizar acordo entre as partes
            estabelecendo diretrizes para compromisso formal de prestação de
            serviços, de um lado, a empresa
            <em
              >TOQUE DIVINO ORQUESTRA, inscrita no CNPJ de n&ordm;
              04.504.978/0001-41, </em
            >estabelecida comercialmente Rua Doze de novembro, 804 - Americana -
            SP ,<em> </em>denominada CONTRATADA e do outro, denominado
            CONTRATANTE, conforme dados preenchidos abaixo:</span
          >
        </p>

        <hr />
        <h2 class="mt-3 mb-3">Responsável pelo Contrato</h2>

        <p>Nome: {{ contract.inscribe.accountable }}</p>
        <p>CPF: {{ contract.inscribe.cpf }}</p>
        <p>RG: {{ contract.inscribe.rg }}</p>
        <p>Telefone: {{ contract.inscribe.pPhone }}</p>
        <p>Celular: {{ contract.inscribe.mobile }}</p>
        <div v-if="contract.engaged != null">
          <hr />
          <h2 class="mb-3 mt-3">Dados dos Noivos</h2>
          <p>
            <span style="font-size: 14px"
              >Noiva: {{ contract.engaged.bride_name }}</span
            >
          </p>

          <p>
            <span style="font-size: 14px"
              >RG: {{ contract.engaged.bride_rg }}&nbsp;&nbsp; &nbsp; &nbsp;CPF:
              {{ contract.engaged.bride_cpf }}&nbsp; &nbsp; &nbsp; Data
              Nascimento:
              {{
                $moment(contract.engaged.bride_birthdate).format("DD/MM/YYYY")
              }}</span
            >
          </p>

          <p>
            <span style="font-size: 14px"
              >Endere&ccedil;o: {{ contract.engaged.bride_address.street }},
              {{ contract.engaged.bride_address.number }}
              {{ contract.engaged.bride_address.complement }},
              {{ contract.engaged.bride_address.neighborhood }},
              {{ contract.engaged.bride_address.city }},
              {{ contract.engaged.bride_address.state }},
              {{ contract.engaged.bride_address.country }}</span
            >
          </p>

          <p>
            <span style="font-size: 14px"
              >Tel: {{ contract.engaged.bride_mobile }}&nbsp; &nbsp; &nbsp;
              &nbsp;e-mail: {{ contract.engaged.bride_email }}</span
            >
          </p>

          <p>
            <span style="font-size: 14px"
              >Noivo: {{ contract.engaged.groom_name }}</span
            >
          </p>

          <p>
            <span style="font-size: 14px"
              >RG: {{ contract.engaged.groom_rg }}&nbsp; &nbsp; &nbsp; CPF:
              {{ contract.engaged.groom_cpf }}&nbsp; &nbsp; &nbsp; Data
              Nascimento:
              {{
                $moment(contract.engaged.groom_birthdate).format("DD/MM/YYYY")
              }}</span
            >
          </p>

          <p>
            <span style="font-size: 14px"
              >Endere&ccedil;o: {{ contract.engaged.groom_address.street }},
              {{ contract.engaged.groom_address.number }}
              {{ contract.engaged.groom_address.complement }},
              {{ contract.engaged.groom_address.neighborhood }},
              {{ contract.engaged.groom_address.city }},
              {{ contract.engaged.groom_address.state }},
              {{ contract.engaged.groom_address.country }}</span
            >
          </p>

          <p>
            <span style="font-size: 14px"
              >Tel: {{ contract.engaged.groom_mobile }}&nbsp; &nbsp; &nbsp;
              &nbsp;e-mail: {{ contract.engaged.groom_email }}</span
            >
          </p>
        </div>
        <hr class="mt-3 mb-3" />
        <p style="text-align: justify">
          <span style="font-size: 14px"
            >A CONTRATADA e CONTRATANTE estabelecem em comum acordo na
            presta&ccedil;&atilde;o e execu&ccedil;&atilde;o de servi&ccedil;os
            musicais, conforme as clausulas que seguem abaixo:</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Clausula 1&ordf; - O valor total dos servi&ccedil;os a serem
            prestados pela CONTRATADA no presente contrato &eacute; de
            R$&nbsp;{{
              contract.agreement.value_total.toString().replace(".", ",")
            }}&nbsp;({{ contract.agreement.value_total_extenso }}) a serem pagos
            da seguinte forma:
          </span>
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Par&aacute;grafo 1&ordm; - &Eacute; de obriga&ccedil;&atilde;o do
            CONTRATANTE efetuar o pagamento de no m&iacute;nimo R$
            {{ contract.agreement.down_payment.replace(".", ",") }}&nbsp;({{
              contract.agreement.down_payment_extenso
            }}) do valor total na data de&nbsp;{{
              $moment(contract.agreement.down_payment_date).format(
                "DD/MM/YYYY"
              )
            }}&nbsp;e o restante, podendo efetuar o pagamento em at&eacute; 15
            (quinze) dias &uacute;teis antes do evento (clausula 2&ordf;), sob
            pena de cancelamento do contrato motivado pelo contratante conforme
            par&aacute;grafo 4&ordm;.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Par&aacute;grafo 2&ordm; - O pagamento poder&aacute; ser feito em
            esp&eacute;cie &nbsp;ou dep&oacute;sito banc&aacute;rio (nesse
            &uacute;ltimo caso, o pagamento ser&aacute; considerado somente
            ap&oacute;s confirma&ccedil;&atilde;o banc&aacute;ria).</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Contas Banc&aacute;rias Dispon&iacute;veis no link:
            www.toquedivino.com/conta , enviar comprovante no email
            financeiro@toquedivino.com, informando data e hor&aacute;rio do
            evento, para que ser providenciado a baixa no sistema.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Par&aacute;grafo 3&ordm; - Na eventualidade de atrasos de
            pagamentos, de cheques sem provis&atilde;o de fundos ou qualquer
            outra forma de inadimpl&ecirc;ncia da Clausula 1&ordf; do presente
            contrato, os d&eacute;bitos arcados pelo CONTRATANTE sofrer&atilde;o
            multa de 2% (dois por cento) e juros de 1% (um por cento) ao
            dia.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Par&aacute;grafo 4&ordm; - Na hip&oacute;tese de cancelamento do
            contrato, por qualquer motivo, sendo a parte CONTRATANTE motivadora
            da dissolu&ccedil;&atilde;o, o mesmo n&atilde;o ter&aacute; direito
            &agrave; devolu&ccedil;&atilde;o do valor pago. Caso ainda o
            CONTRATANTE n&atilde;o tenha pagado nenhum valor contratado e se for
            dele o motivo da dissolu&ccedil;&atilde;o do contrato, o mesmo
            pagar&aacute; a titulo de multa por quebra de contrato de 30%
            (trinta por cento) do valor contido na clausula 1&ordf; e desde que,
            avisado com pelo menos 60 dias de anteced&ecirc;ncia.
          </span>
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Par&aacute;grafo 5&ordm; - O n&atilde;o pagamento do valor total
            deste contrato at&eacute; o prazo estabelecido na clausula 1&ordf;,
            par&aacute;grafo 1&ordm; deste documento desobriga automaticamente a
            CONTRATADA da execu&ccedil;&atilde;o dos servi&ccedil;os aqui
            descritos ao CONTRATANTE, salvo se, este, quitar o valor restante
            exclusivamente em esp&eacute;cie e/ou concordar com a
            condi&ccedil;&atilde;o de uma cau&ccedil;&atilde;o da pra&ccedil;a
            no valor total deste documento e que ser&aacute; devolvido
            ap&oacute;s quita&ccedil;&atilde;o do saldo devedor acrescido de
            multa e juros conforme par&aacute;grafo 3&ordm; acima. O
            descumprimento do prazo pelo CONTRATANTE e a n&atilde;o
            execu&ccedil;&atilde;o do trabalho por esse motivo ou qualquer outro
            motivado por este, n&atilde;o diminui nem exclui o CONTRATANTE da
            quita&ccedil;&atilde;o deste documento em seu valor total dentro dos
            prazos estabelecidos.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Clausula 2&ordf; - Fica a CONTRATADA obrigada, ap&oacute;s a
            obriga&ccedil;&atilde;o do CONTRATANTE contida na clausula 1&ordf;,
            a prestar servi&ccedil;os de execu&ccedil;&atilde;o de
            m&uacute;sicas para a cerim&ocirc;nia de casamento do CONTRATANTE
            que, ser&aacute; realizado na data, hor&aacute;rio e local abaixo
            especificado.&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span
          >
        </p>

        <p>
          <span style="font-size: 14px">lista_locais_formacao</span>
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Par&aacute;grafo &Uacute;nico &ndash; &Eacute; de responsabilidade
            da CONTRATADA levar seus instrumentos musicais e equipamentos de som
            para o evento contratado (caso a igreja, institui&ccedil;&atilde;o
            ou local do referido evento n&atilde;o possuam) inerentes &agrave;
            boa execu&ccedil;&atilde;o do trabalho. Os equipamentos e
            instrumentos da CONTRATADA s&atilde;o espec&iacute;ficos para a
            execu&ccedil;&atilde;o do trabalho desta, n&atilde;o sendo dela a
            obriga&ccedil;&atilde;o de sonorizar qualquer outro tipo de ambiente
            ou situa&ccedil;&atilde;o e ainda, terceiros.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Clausula 3&ordf; - O CONTRATANTE deve com anteced&ecirc;ncia
            escolher o repert&oacute;rio de musicas para seu casamento de acordo
            com cada momento da cerim&ocirc;nia, sem limite de
            m&uacute;sicas.</span
          >
        </p>

        <p>
          <span style="font-size: 14px"
            >Par&aacute;grafo 1&ordm; - A CONTRATADA limitar-se-&aacute; a
            auxiliar e orientar as escolhas de musicas para a cerim&ocirc;nia do
            CONTRATANTE, sem, por&eacute;m, influenciar na decis&atilde;o final.
            At&eacute; uma musica que n&atilde;o tenha no repert&oacute;rio da
            CONTRATADA &eacute; inclu&iacute;da sem custo adicional.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Par&aacute;grafo 2&ordm; - O repert&oacute;rio escolhido pelo
            CONTRATANTE poder&aacute; ser alterado em at&eacute; 60 (sessenta)
            dias antes da cerim&ocirc;nia contida na clausula 1&ordf;,
            n&atilde;o podendo mais faz&ecirc;-lo ap&oacute;s esse prazo, salvo
            se a CONTRATADA julgar poss&iacute;vel.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Clausula 4&ordf; - É de obrigação da CONTRATADA, após a assinatura
            deste contrato, estar no local, data e horário estabelecido na
            Clausula 2&ordf;, para a realização do trabalho.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Parágrafo 1&ordm; - Os serviços não serão realizados pela
            CONTRATADA na eventualidade de ocorrer falta de energia elétrica no
            evento, falta de segurança para a equipe CONTRATADA ou até casos
            extremos (acidentes graves ou até morte de membros da equipe), não
            cabendo a parte CONTRATANTE pedido de indenização.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Parágrafo 2&ordm; - Em caso de ocorrência ou nítida ameaça de
            chuvas, temporais ou qualquer outra espécie de adversidade climática
            que possa danificar instrumentos e equipamentos, prejudicar a saúde
            dos integrantes da equipe, sendo o local ambiente aberto ou exposto,
            a CONTRATADA se reserva o direito de desligar e desmontar
            imediatamente todo o equipamento e/ou não montar e nem ligar, caso
            ainda não tenha sido feito, cancelando o trabalho se o CONTRATANTE
            não providenciar local seguro para a execução do trabalho, não dando
            direito ao CONTRATANTE pedido de indenização ou qualquer estorno de
            valor pago.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Parágrafo 3&ordm; - O respectivo serviço musical contratado, terá
            sua duração de 1h (uma hora) a partir do horário convencionado na
            Clausula 2&ordf;, com tolerância a mais de 15 (quinze minutos).
            Qualquer tempo a mais que a CONTRATADA permanecer no evento devido a
            atrasos, será cobrado um adicional com mesmo valor deste documento
            contido na cláusula 1&ordf;, cabendo à CONTRATADA e não ao
            CONTRATANTE a decisão de permanecer ou não no evento após o tempo
            determinado.</span
          >
        </p>

        <p>
          <span style="font-size: 14px"
            >Parágrafo 4&ordm; - Na casualidade de ocorrer atraso para o inicio
            da cerimônia, serão tolerados pela CONTRATADA atrasos de no máximo
            20 (vinte minutos) após o horário acima declarado, não havendo
            responsabilidade por parte da CONTRATADA em continuar no local após
            esse período.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Clausula 5&ordf; - A CONTRATADA não se responsabiliza por
            ausências, ou ainda, por eventuais danos materiais ou morais
            causados por terceiros e/ou músicos terceiros contratados pelo
            CONTRATANTE para o mesmo evento.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Clausula 6&ordf; - Os danos causados nos equipamentos/instrumentos
            de som da CONTRATADA ocasionados pelo CONTRATANTE, seus convidados
            ou ainda, terceiros contratados pelo CONTRATANTE para o evento,
            serão de inteira responsabilidade do CONTRATANTE, sob pena de
            pagamentos em Dobro dos Danos ocasionados, caso a CONTRATADA tenha
            que ingressar com Ação Judicial.</span
          >
        </p>

        <p style="text-align: justify">
          <span style="font-size: 14px"
            >Clausula 7&ordf; - O serviço de assistente que a CONTRATADA oferece
            é exclusivo para auxilio da equipe de músicos, não se obrigando a
            responsabilizar por demais encargos.</span
          >
        </p>

        <p>
          <span style="font-size: 14px"
            >Clausula 8&ordf; - Fica eleito o Foro da cidade de Americana para
            soluções de quaisquer questões decorrentes deste termo, com expressa
            renúncia a qualquer outro, por mais privilegiado que seja.</span
          >
        </p>

        <p>
          <span style="font-size: 14px"
            >E por estarem de acordo com as condições estabelecidas, as partes,
            CONTRATANTE e CONTRATADA assinam este instrumento em 02 (duas) vias
            de igual teor e eficácia.</span
          >
        </p>
      </div>
      <!-- Fim Contrato -->
      <v-divider></v-divider>
      <v-sheet rounded="lg" class="pa-3 ma-3" color="grey lighten-4">
        <v-spacer></v-spacer>
        <v-btn depressed color="primary"><v-icon>mdi-draw-pen</v-icon>Assinar</v-btn>
      </v-sheet>
    </v-sheet>
    <v-overlay :value="loading"
      ><v-progress-circular indeterminate></v-progress-circular
    ></v-overlay>
  </div>
</template>

<script>
export default {
  name: "SignatureSign",

  data() {
    return {
      apiURL: process.env.VUE_APP_URL,
      contract: { inscribe: "", agreement: "", signature: "", engaged: "" },
      loading: true,
      loaded: false,
    };
  },

  mounted() {},

  methods: {
    getContract: async function () {
      let response = await axios.get(
        this.apiURL +
          "/signatures/getForSign/" +
          this.$route.params.signature +
          "/" +
          this.$route.params.agreement
      );
      if (response.data) {
        this.loaded = true;
        this.loading = false;
        this.contract = response.data;
        this.contract.agreement.down_payment_extenso = this.$extenso(
          response.data.agreement.down_payment.replace(".", ","),
          { number: { decimal: "formal" }, mode: "currency" }
        );
        this.contract.agreement.value_total_extenso = this.$extenso(
          response.data.agreement.value_total.toString().replace(".", ","),
          { number: { decimal: "formal" }, mode: "currency" }
        );
      }
    },
  },

  created: async function () {
    await this.getContract();
  },
};
</script>

<style scoped>
</style>