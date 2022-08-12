import axios from "axios";

export async function requestGet(url, params = {}, config = {}) {
  try {
    const res = await axios.get(url, {
      ...config,
      params,
    });
    return res.data;
  } catch (error) {
    if (error.response) {
      if (error.response.data) {
        return error.response.data;
      } else {
        return {
          status: 0,
          msg: error.response.statusText,
        };
      }
    } else {
      return {
        status: 0,
        msg: error,
      };
    }
  }
}

export async function requestPost(url, data = {}, config = {}) {
  try {
    const res = await axios.post(url, data, config);
    return res.data;
  } catch (error) {
    if (error.response) {
      if (error.response.data) {
        return error.response.data;
      } else {
        return {
          status: 0,
          msg: error.response.statusText,
        };
      }
    } else {
      return {
        status: 0,
        msg: error,
      };
    }
  }
}
