import { requestGet, requestPost } from "../utils/request";

export const getListPosts = async (page, limit) => {
  return requestGet(`${process.env.REACT_APP_BASE_URL}/api/posts`, {
    page,
    limit,
  });
};

export const createPost = async (postData) => {
  return requestPost(`${process.env.REACT_APP_BASE_URL}/api/posts`, postData);
};

export const getPost = async (postId) => {
  return requestGet(`${process.env.REACT_APP_BASE_URL}/api/posts/${postId}`);
};

export const updatePost = async (postId, postData) => {
  return requestPost(
    `${process.env.REACT_APP_BASE_URL}/api/posts/${postId}`,
    postData
  );
};

export const createPostMeta = async (postId, metaData) => {
  return requestPost(
    `${process.env.REACT_APP_BASE_URL}/api/posts/${postId}/meta`,
    metaData
  );
};

export const updatePostMeta = async (postId, metaId, metaData) => {
  return requestPost(
    `${process.env.REACT_APP_BASE_URL}/api/posts/${postId}/meta/${metaId}`,
    metaData
  );
};
