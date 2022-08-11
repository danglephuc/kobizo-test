import React, {useEffect, useRef, useState} from 'react';
import { Form, Input, Select, Button, Loading } from 'element-react';
import {useNavigate, useParams} from "react-router-dom";
import {createPost, getPost, updatePost} from "../../services/posts";

const EditPost = () => {
    const { postId } = useParams();
    const isUpdate = postId !== 'new';
    const [loading, setLoading] = useState(false);
    const [formData, setFormData] = useState({
        title: '',
        content: '',
        status: 1,
    });
    const rules = {
        title: [
            { required: true, message: 'Please input title', trigger: 'blur' }
        ],
        content: [
            { required: true, message: 'Please input content', trigger: 'blur' }
        ],
    }
    const navigate = useNavigate();
    const formRef = useRef(null);

    useEffect(() => {
        if(isUpdate) {
            fetchPost(postId);
        }
    }, []);

    const fetchPost = async (postId) => {
        setLoading(true);
        try {
            const res = await getPost(postId);
            if(res.status) {
                setFormData({
                    title: res.data.title,
                    content: res.data.content,
                    status: res.data.status,
                });
            } else {
                // TODO: show error
            }
        } catch (e) {
            console.error(e);
        } finally {
            setLoading(false);
        }
    };

    const onChange = (key, value) => {
        setFormData({
            ...formData,
            [key]: value,
        });
    };

    const onSubmit = () => {
        formRef.current.validate(async (isValid) => {
            if (isValid) {
                try {
                    setLoading(true);
                    let res;
                    if (isUpdate) {
                        res = await updatePost(postId, {
                            title: formData.title,
                            content: formData.content,
                            status: formData.status,
                        });
                    } else {
                        res = await createPost({
                            title: formData.title,
                            content: formData.content,
                            status: formData.status,
                        });
                    }
                    if (res.status) {
                        navigate(`/posts`);
                    } else {
                        // TODO: show error
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    setLoading(false);
                }
            }
        });
    }

    const onCancel = () => {
        navigate(`/posts`);
    }

    return (
        <div className="container mx-auto my-10">
            <div className="flex justify-between items-center">
                <div className="text-2xl font-bold leading-5">{isUpdate ? 'Update Post' : 'Add Post'}</div>
            </div>

            <div className="w-100 mt-10">
                <Loading loading={loading}>
                    <Form ref={formRef} className="en-US" model={formData} labelWidth="120" rules={rules}>
                        <Form.Item label="Title" prop="title">
                            <Input value={formData.title} onChange={onChange.bind(this, 'title')}/>
                        </Form.Item>
                        <Form.Item label="Content" prop="content">
                            <Input value={formData.content} onChange={onChange.bind(this, 'content')}/>
                        </Form.Item>
                        <Form.Item label="Status" prop="status">
                            <Select value={formData.status} placeholder="Select Status" onChange={onChange.bind(this, 'status')}>
                                <Select.Option label="Active" value={1}/>
                                <Select.Option label="Inactive" value={0}/>
                            </Select>
                        </Form.Item>
                        <Form.Item>
                            <Button type="primary" onClick={onSubmit}>{ isUpdate ? 'Update' : 'Create'}</Button>
                            <Button onClick={onCancel}>Cancel</Button>
                        </Form.Item>
                    </Form>
                </Loading>
            </div>
        </div>
    );
};
export default EditPost;
