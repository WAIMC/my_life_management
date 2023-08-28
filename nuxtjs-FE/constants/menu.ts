import { useI18n } from "~/plugins/i18n.config";

// Use the third api outside setup function
const { t } = useI18n.global;

// Define message label
const M_MENU_SIDEBARS = "Menu.Sidebars.";
const M_INFRASTRUCTURE = M_MENU_SIDEBARS + "infrastructure.";
const M_INFRASTRUCTURE_FE = M_INFRASTRUCTURE + "front_end.";
const M_INFRASTRUCTURE_BA = M_INFRASTRUCTURE + "back_end.";
const M_INFRASTRUCTURE_DB = M_INFRASTRUCTURE + "database.";
const M_TEMPLATE_RESPONSE = M_MENU_SIDEBARS + "template_response.";
const M_TEMPLATE_RESPONSE_PAGE = M_TEMPLATE_RESPONSE + "page.";
const M_TEMPLATE_RESPONSE_COMPONENT = M_TEMPLATE_RESPONSE + "component.";
const M_NETWORK = M_MENU_SIDEBARS + "network.";
const M_AUTHORIZATION = M_MENU_SIDEBARS + "authorization.";
const M_EXPERIENCE = M_MENU_SIDEBARS + "experience.";
const M_LAYOUT = M_MENU_SIDEBARS + "layout.";

// Define route
const R_INFRASTRUCTURE = "/infrastructure";
const R_INFRASTRUCTURE_FE = R_INFRASTRUCTURE + "/front-end";
const R_INFRASTRUCTURE_BA = R_INFRASTRUCTURE + "/back-end";
const R_INFRASTRUCTURE_DB = R_INFRASTRUCTURE + "/database";
const R_TEMPLATE_RESPONSE = "/template-response";
const R_TEMPLATE_RESPONSE_PAGE = R_TEMPLATE_RESPONSE + "/page";
const R_TEMPLATE_RESPONSE_COMPONENT = R_TEMPLATE_RESPONSE + "/component";
const R_NETWORK = "/network";
const R_AUTHORIZATION = "/authorization";
const R_EXPERIENCE = "/experience";
const R_LAYOUT = "/layout";

export const MenuSidebarsConstant = [
  {
    id: 'lv1i1',
    label: t(M_MENU_SIDEBARS + "dashboard"),
    route: "/dashboard",
    icon: "fa-house",
  },
  {
    id: 'lv1i2',
    label: t(M_INFRASTRUCTURE + "label"),
    icon: "fa-industry",
    isShow: false,
    items: [
      {
        id: 'lv1i2i1',
        label: t(M_INFRASTRUCTURE_FE + "label"),
        isShow: false,
        items: [
          {
            id: 'lv1i2i1i1',
            label: t(M_INFRASTRUCTURE_FE + "api"),
            route: R_INFRASTRUCTURE_FE + "/api",
          },
          {
            id: 'lv1i2i1i2',
            label: t(M_INFRASTRUCTURE_FE + "tree_view"),
            route: R_INFRASTRUCTURE_FE + "/tree-view",
          },
          {
            id: 'lv1i2i1i3',
            label: t(M_INFRASTRUCTURE_FE + "proxy"),
            route: R_INFRASTRUCTURE_FE + "/proxy",
          },
          {
            id: 'lv1i2i1i4',
            label: t(M_INFRASTRUCTURE_FE + "port"),
            route: R_INFRASTRUCTURE_FE + "/port",
          },
          {
            id: 'lv1i2i1i5',
            label: t(M_INFRASTRUCTURE_FE + "host"),
            route: R_INFRASTRUCTURE_FE + "/host",
          },
          {
            id: 'lv1i2i1i6',
            label: t(M_INFRASTRUCTURE_FE + "ui"),
            route: R_INFRASTRUCTURE_FE + "/ui",
          },
          {
            id: 'lv1i2i1i7',
            label: t(M_INFRASTRUCTURE_FE + "ux"),
            route: R_INFRASTRUCTURE_FE + "/ux",
          },
          {
            id: 'lv1i2i1i8',
            label: t(M_INFRASTRUCTURE_FE + "typography"),
            route: R_INFRASTRUCTURE_FE + "/typography",
          },
          {
            id: 'lv1i2i1i9',
            label: t(M_INFRASTRUCTURE_FE + "color"),
            route: R_INFRASTRUCTURE_FE + "/color",
          },
        ],
      },
      {
        id: 'lv1i2i2',
        label: t(M_INFRASTRUCTURE_BA + "label"),
        isShow: false,
        items: [
          {
            id: 'lv1i2i2i1',
            label: t(M_INFRASTRUCTURE_BA + "host"),
            route: R_INFRASTRUCTURE_BA + "/host",
          },
          {
            id: 'lv1i2i2i2',
            label: t(M_INFRASTRUCTURE_BA + "port"),
            route: R_INFRASTRUCTURE_BA + "/port",
          },
          {
            id: 'lv1i2i2i3',
            label: t(M_INFRASTRUCTURE_BA + "monitor"),
            route: R_INFRASTRUCTURE_BA + "/monitor",
          },
          {
            id: 'lv1i2i2i4',
            label: t(M_INFRASTRUCTURE_BA + "tree_view"),
            route: R_INFRASTRUCTURE_BA + "/tree-view",
          },
          {
            id: 'lv1i2i2i5',
            label: t(M_INFRASTRUCTURE_BA + "api"),
            route: R_INFRASTRUCTURE_BA + "/api",
          },
          {
            id: 'lv1i2i2i6',
            label: t(M_INFRASTRUCTURE_BA + "connection_activity"),
            route: R_INFRASTRUCTURE_BA + "/connection-activity",
          },
        ],
      },
      {
        id: 'lv1i2i3',
        label: t(M_INFRASTRUCTURE_DB + "label"),
        isShow: false,
        items: [
          {
            id: 'lv1i2i3i1',
            label: t(M_INFRASTRUCTURE_DB + "host"),
            route: R_INFRASTRUCTURE_DB + "/host",
          },
          {
            id: 'lv1i2i3i2',
            label: t(M_INFRASTRUCTURE_DB + "port"),
            route: R_INFRASTRUCTURE_DB + "/port",
          },
          {
            id: 'lv1i2i3i3',
            label: t(M_INFRASTRUCTURE_DB + "monitor"),
            route: R_INFRASTRUCTURE_DB + "/monitor",
          },
          {
            id: 'lv1i2i3i4',
            label: t(M_INFRASTRUCTURE_DB + "tree_view"),
            route: R_INFRASTRUCTURE_DB + "/tree-view",
          },
          {
            id: 'lv1i2i3i5',
            label: t(M_INFRASTRUCTURE_DB + "connection_activity"),
            route: R_INFRASTRUCTURE_DB + "/connection-activity",
          },
        ],
      },
    ],
  },
  {
    id: 'lv1i3',
    label: t(M_TEMPLATE_RESPONSE + "label"),
    icon: "fa-layer-group",
    isShow: false,
    items: [
      {
        id: 'lv1i3i1',
        label: t(M_TEMPLATE_RESPONSE_PAGE + "label"),
        isShow: false,
        items: [
          {
            id: 'lv1i3i1i1',
            label: t(M_TEMPLATE_RESPONSE_PAGE + "login"),
            route: R_TEMPLATE_RESPONSE_PAGE + "/login",
          },
          {
            id: 'lv1i3i1i2',
            label: t(M_TEMPLATE_RESPONSE_PAGE + "sign_up"),
            route: R_TEMPLATE_RESPONSE_PAGE + "/sign-up",
          },
          {
            id: 'lv1i3i1i3',
            label: t(M_TEMPLATE_RESPONSE_PAGE + "register_user"),
            route: R_TEMPLATE_RESPONSE_PAGE + "/register-user",
          },
          {
            id: 'lv1i3i1i4',
            label: t(M_TEMPLATE_RESPONSE_PAGE + "user_profile"),
            route: R_TEMPLATE_RESPONSE_PAGE + "/user-profile",
          },
        ]
      },
      {
        id: 'lv1i3i2',
        label: t(M_TEMPLATE_RESPONSE_COMPONENT + "label"),
        isShow: false,
        items: [
          {
            id: 'lv1i3i2i1',
            label: t(M_TEMPLATE_RESPONSE_COMPONENT + "table"),
            route: R_TEMPLATE_RESPONSE_COMPONENT + "/table",
          },
          {
            id: 'lv1i3i2i2',
            label: t(M_TEMPLATE_RESPONSE_COMPONENT + "form"),
            route: R_TEMPLATE_RESPONSE_COMPONENT + "/form",
          },
          {
            id: 'lv1i3i2i3',
            label: t(M_TEMPLATE_RESPONSE_COMPONENT + "chart"),
            route: R_TEMPLATE_RESPONSE_COMPONENT + "/chart",
          },
          {
            id: 'lv1i3i2i4',
            label: t(M_TEMPLATE_RESPONSE_COMPONENT + "calendar"),
            route: R_TEMPLATE_RESPONSE_COMPONENT + "/calendar",
          },
          {
            id: 'lv1i3i2i5',
            label: t(M_TEMPLATE_RESPONSE_COMPONENT + "map"),
            route: R_TEMPLATE_RESPONSE_COMPONENT + "/map",
          },
          {
            id: 'lv1i3i2i6',
            label: t(M_TEMPLATE_RESPONSE_COMPONENT + "button"),
            route: R_TEMPLATE_RESPONSE_COMPONENT + "/button",
          },
          {
            id: 'lv1i3i2i7',
            label: t(M_TEMPLATE_RESPONSE_COMPONENT + "color"),
            route: R_TEMPLATE_RESPONSE_COMPONENT + "/color",
          },
          {
            id: 'lv1i3i2i8',
            label: t(M_TEMPLATE_RESPONSE_COMPONENT + "alert"),
            route: R_TEMPLATE_RESPONSE_COMPONENT + "/alert",
          },
          {
            id: 'lv1i3i2i9',
            label: t(M_TEMPLATE_RESPONSE_COMPONENT + "typography"),
            route: R_TEMPLATE_RESPONSE_COMPONENT + "/typography",
          },
          {
            id: 'lv1i3i2i10',
            label: t(M_TEMPLATE_RESPONSE_COMPONENT + "widget"),
            route: R_TEMPLATE_RESPONSE_COMPONENT + "/widget",
          },
        ],
      },
    ],
  },
  {
    id: 'lv1i4',
    label: t(M_NETWORK + "label"),
    icon: "fa-diagram-project",
    isShow: false,
    items: [
      {
        id: 'lv1i4i1',
        label: t(M_NETWORK + "email"),
        route: R_NETWORK + "/email",
      },
      {
        id: 'lv1i4i2',
        label: t(M_NETWORK + "facebook"),
        route: R_NETWORK + "/facebook",
      },
      {
        id: 'lv1i4i3',
        label: t(M_NETWORK + "linkedin"),
        route: R_NETWORK + "/linkedin",
      },
    ],
  },
  {
    id: 'lv1i5',
    label: t(M_AUTHORIZATION + "label"),
    icon: "fa-skull-crossbones",
    isShow: false,
    items: [
      {
        id: 'lv1i5i1',
        label: t(M_AUTHORIZATION + "decentralize"),
        route: R_AUTHORIZATION + "/decentralize",
      },
      {
        id: 'lv1i5i2',
        label: t(M_AUTHORIZATION + "role"),
        route: R_AUTHORIZATION + "/role",
      },
      {
        id: 'lv1i5i3',
        label: t(M_AUTHORIZATION + "api"),
        route: R_AUTHORIZATION + "/api",
      },
      {
        id: 'lv1i5i4',
        label: t(M_AUTHORIZATION + "feature"),
        route: R_AUTHORIZATION + "/feature",
      },
      {
        id: 'lv1i5i5',
        label: t(M_AUTHORIZATION + "policy"),
        route: R_AUTHORIZATION + "/policy",
      },
      {
        id: 'lv1i5i6',
        label: t(M_AUTHORIZATION + "infrastructure"),
        route: R_AUTHORIZATION + "/infrastructure",
      },
    ],
  },
  {
    id: 'lv1i6',
    label: t(M_EXPERIENCE + "label"),
    icon: "fa-book",
    isShow: false,
    items: [
      {
        id: 'lv1i6i1',
        label: t(M_EXPERIENCE + "category"),
        route: R_EXPERIENCE + "/category",
      },
      {
        id: 'lv1i6i2',
        label: t(M_EXPERIENCE + "skill"),
        route: R_EXPERIENCE + "/skill",
      },
      {
        id: 'lv1i6i3',
        label: t(M_EXPERIENCE + "skill_description"),
        route: R_EXPERIENCE + "/skill-description",
      },
    ],
  },
  {
    id: 'lv1i7',
    label: t(M_LAYOUT + "label"),
    icon: "fa-layer-group",
    isShow: false,
    items: [
      {
        id: 'lv1i7i1',
        label: t(M_LAYOUT + "slider"),
        route: R_LAYOUT + "/slider",
      },
      {
        id: 'lv1i7i2',
        label: t(M_LAYOUT + "banner"),
        route: R_LAYOUT + "/banner",
      },
      {
        id: 'lv1i7i3',
        label: t(M_LAYOUT + "social"),
        route: R_LAYOUT + "/social",
      },
      {
        id: 'lv1i7i4',
        label: t(M_LAYOUT + "link"),
        route: R_LAYOUT + "/link",
      },
      {
        id: 'lv1i7i5',
        label: t(M_LAYOUT + "translation"),
        route: R_LAYOUT + "/translation",
      },
    ],
  },
];
