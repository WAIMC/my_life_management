import axios, { AxiosError, AxiosRequestConfig, AxiosResponse } from "axios";
import { APIResponse } from "./api-response";

/**
 * Connect to api by method get
 * @param string url
 * @param any param
 */
async function connectGet<T>(url: string, param: any) {
  return axios
    .get(url, { params: param })
    .then((res) => {
      return new APIResponse<T>(res);
    })
    .catch((error: AxiosError) => {
      const response = error.response;
      // const response: AxiosResponse<any> | undefined = error.response;
      if (response) {
        return new APIResponse<T>(response);
      } else {
        return null;
      }
    });
}

/**
 * Connect to api by method post
 * @param string url
 * @param any data
 * @param AxiosRequestConfig|undefined config
 * @return object
 */
async function connectPost<T>(
  url: string,
  data: any,
  config?: AxiosRequestConfig | undefined
) {
  return axios
    .post(url, data, config)
    .then((res) => {
      return new APIResponse<T>(res);
    })
    .catch((error: AxiosError) => {
      const response = error.response;
      if (response) {
        return new APIResponse<T>(response);
      } else {
        return null;
      }
    });
}

/**
 * Connect to api by method put
 * @param string url
 * @param any data
 * @return object
 */
async function connectPut<T>(url: string, data: any) {
  return axios
    .put(url, data)
    .then((res) => {
      return new APIResponse<T>(res);
    })
    .catch((error: AxiosError) => {
      const response = error.response;
      if (response) {
        return new APIResponse<T>(response);
      } else {
        return null;
      }
    });
}

/**
 * Connect to api by method delete
 * @param string url
 * @param any data
 * @return object
 */
async function connectDelete<T>(url: string, data: any) {
  return axios
    .delete(url, data)
    .then((res) => {
      return new APIResponse<T>(res);
    })
    .catch((error: AxiosError) => {
      const response = error.response;
      if (response) {
        return new APIResponse<T>(response);
      } else {
        return null;
      }
    });
}

/**
 * Action call api by method get
 * @param string url
 * @param any param
 * @return array | null
 */
async function actionGet<T>(url: string, param?: any) {
  const response = await connectGet<T>(url, param);

  return handleResponse(response);
}

/**
 * Action call api by method post
 * @param string url
 * @param any data
 * @return array | null
 */
async function actionPost<T>(url: string, data: any) {
  const response = await connectPost<T>(url, data);
  return handleResponse(response);
}

/**
 * Action call api by method put
 * @param string url
 * @param any data
 * @return array | null
 */
async function actionPut<T>(url: string, data: any) {
  const response = await connectPut<T>(url, data);
  return handleResponse(response);
}

/**
 * Action call api by method delete
 * @param string url
 * @param any data
 * @return array | null
 */
async function actionDelete<T>(url: string, data: any) {
  const response = await connectDelete<T>(url, data);
  return handleResponse(response);
}

/**
 * Handle response api
 * @param APIResponse response
 * @return array | null
 */
function handleResponse<T>(response: APIResponse<T> | null) {
  if (response === null) {
    console.log("response null");
    return null;
  } else if (response.hasError() || response.data === null) {
    console.log("response error");
    return null;
  }

  return response.data;
}

export { actionGet, actionPost, actionPut, actionDelete };
