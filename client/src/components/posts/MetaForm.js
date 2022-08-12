import React, { useState, useEffect, useRef } from "react";
import PropTypes from "prop-types";
import {
  Loading,
  Dialog,
  Button,
  Form,
  Input,
  MessageBox,
} from "element-react";
import { createPostMeta, updatePostMeta } from "../../services/posts";

export const MetaForm = (props) => {
  const { showModal, closeModal, postId, meta } = props;

  const [loading, setLoading] = useState(false);
  const [formData, setFormData] = useState({
    key: "",
    value: "",
  });
  const rules = {
    key: [{ required: true, message: "Please input key", trigger: "blur" }],
    value: [{ required: true, message: "Please input value", trigger: "blur" }],
  };

  useEffect(() => {
    if (!showModal) {
      return;
    }
    if (meta) {
      setFormData({
        key: meta.key,
        value: meta.value,
      });
    } else {
      setFormData({
        key: "",
        value: "",
      });
    }
  }, [showModal]);

  const formRef = useRef(null);

  const onSubmit = () => {
    formRef.current.validate(async (isValid) => {
      if (isValid) {
        try {
          setLoading(true);
          let res;
          if (meta) {
            res = await updatePostMeta(postId, meta.id, {
              key: formData.key,
              value: formData.value,
            });
          } else {
            res = await createPostMeta(postId, {
              key: formData.key,
              value: formData.value,
            });
          }
          if (res.status) {
            closeModal(res.data);
          } else {
            await MessageBox.alert("Error", res.msg);
          }
        } catch (e) {
          console.error(e);
        } finally {
          setLoading(false);
        }
      }
    });
  };

  const onChange = (key, value) => {
    setFormData({
      ...formData,
      [key]: value,
    });
  };

  return (
    <Dialog
      title={`${meta ? "Update" : "Create"} Meta`}
      size="tiny"
      visible={showModal}
      onCancel={() => {
        closeModal(null);
      }}
      lockScroll={false}
    >
      <Loading loading={loading}>
        <Dialog.Body>
          <Form
            ref={formRef}
            className="en-US"
            model={formData}
            labelWidth="120"
            rules={rules}
          >
            <Form.Item label="Key" prop="key">
              <Input
                value={formData.key}
                onChange={onChange.bind(this, "key")}
              />
            </Form.Item>
            <Form.Item label="Value" prop="value">
              <Input
                value={formData.value}
                onChange={onChange.bind(this, "value")}
              />
            </Form.Item>
          </Form>
        </Dialog.Body>
        <Dialog.Footer className="dialog-footer">
          <Button
            onClick={() => {
              closeModal(null);
            }}
          >
            Cancel
          </Button>
          <Button type="primary" onClick={onSubmit}>
            {meta ? "Update" : "Create"}
          </Button>
        </Dialog.Footer>
      </Loading>
    </Dialog>
  );
};

MetaForm.propTypes = {
  showModal: PropTypes.bool,
  closeModal: PropTypes.func,
  postId: PropTypes.string,
  meta: PropTypes.object,
};

export default MetaForm;
