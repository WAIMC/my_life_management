import { AxiosResponse } from "axios";

interface _Response_<T> {
  data: T;
  error?: {
    status?: boolean;
    code?: number;
    message?: { [key: string]: string[] } | string;
    transfer_error?: boolean;
  };
}

export class APIResponse<T> {
  private res;

  // public constructor(res: AxiosResponse<_Response_<T>>) {
  //   this.res = res;
  // }

  public constructor(res: AxiosResponse<any>) {
    this.res = res;
  }

  /**
   * Get status code of response
   * @return number
   */
  public get statusCode() {
    return this.res?.status;
  }

  /**
   * Get data from response
   * @return json
   */
  public get data(): T {
    // return this.res.data.data;
    return this.res?.data;
  }

  /**
   * Check response has error
   * @return boolean
   */
  public hasError() {
    return (this.res?.status != 200 && this.res?.status != 201) 
      || !this.res?.data 
      || this.res?.data?.error?.status === true
      ? true
      : false;
  }

  /**
   * Get message from response
   * @return string[]
   */
  public getMessageResponse() {
    if (!this.res?.data?.error) {
      return [];
    }

    return this.res?.data?.error?.message;
  }
}
