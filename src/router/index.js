import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import Customer from "../views/Customer.vue";
import CustomerHome from "../components/CustomerHome.vue";
import CustomerAccount from "../components/CustomerAccount.vue";
import CustomerInscribe from "../components/CustomerInscribe.vue";
import CustomerEvent from "../components/CustomerEvent.vue";
import CustomerEngaged from "../components/CustomerEngaged.vue";
import CustomerCommitte from "../components/CustomerCommitte.vue";
import CustomerRepertoy from "../components/CustomerRepertory.vue";
import CustomerBudget from "../components/CustomerBudget.vue";
import CustomerAgreement from "../components/CustomerAgreement.vue";
import CustomerLogin from "../components/CustomerLogin.vue";
import Dashboard from "../views/Dashboard.vue";
import DashboardHome from "../components/DashboardHome.vue";
import DashboardUsers from "../components/DashboardUsers.vue";
import DashboardTaxes from "../components/DashboardTaxes.vue";
import DashboardServices from "../components/DashboardServices.vue";
import DashboardInstruments from "../components/DashboardInstruments.vue";
import DashboardFormations from "../components/DashboardFormations.vue";
import DashboardMusics from "../components/DashboardMusics.vue";
import DashboardMoments from "../components/DashboardMoments.vue";
import DashboardLeads from "../components/DashboardLeads.vue";
import DashboardInscribes from "../components/DashboardInscribes.vue";
import DashboardContracts from "../components/DashboardContracts.vue";
import DashboardSignatures from "../components/DashboardSignatures.vue";
import DashboardLogin from "../components/DashboardLogin.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
  },
  {
    path: "/customer",
    name: "Customer",
    component: Customer,
    children: [
      {
        path: "home",
        component: CustomerHome,
      },
      {
        path: "account",
        component: CustomerAccount,
      },
      {
        path: "inscribe",
        component: CustomerInscribe,
      },
      {
        path: "event",
        component: CustomerEvent,
      },
      {
        path: "engaged",
        component: CustomerEngaged
      },
      {
        path: "committe",
        component: CustomerCommitte
      },
      {
        path: "repertory",
        component: CustomerRepertoy,
      },
      {
        path: "budget",
        component: CustomerBudget,
      },
      {
        path: "agreement",
        component: CustomerAgreement,
      },
      {
        path: "login",
        component: CustomerLogin,
      },
    ],
  },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: Dashboard,
    children: [
      {
        path: "home",
        component: DashboardHome
      },
      {
        path: "users",
        component: DashboardUsers
      },
      {
        path: "taxes",
        component: DashboardTaxes
      },
      {
        path: "services",
        component: DashboardServices
      },
      {
        path: "instruments",
        component: DashboardInstruments
      },
      {
        path: "formations",
        component: DashboardFormations
      },
      {
        path: "moments",
        component: DashboardMoments
      },
      {
        path: "musics",
        component: DashboardMusics
      },
      {
        path: "leads",
        component: DashboardLeads
      },
      {
        path: "inscribes",
        component: DashboardInscribes
      },
      {
        path: "contracts",
        component: DashboardContracts
      },
      {
        path: "signatures",
        component: DashboardSignatures
      },
      {
        path: "login",
        component: DashboardLogin
      }
    ]
  }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

export default router;
