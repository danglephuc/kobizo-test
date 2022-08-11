import React from 'react';
import { Button } from 'element-react';
import {PostTable} from "../../components/posts/PostTable";
import {useNavigate} from "react-router-dom";

const ListPost = () => {
    const navigate = useNavigate();

    const newPost = () => {
        navigate(`/posts/new`);
    };

    return (
        <div className="container mx-auto my-10">
            <div className="flex justify-between items-center">
                <div className="text-2xl font-bold leading-5">List Post</div>
                <Button
                    type="primary"
                    onClick={newPost}
                >
                    Add Post
                </Button>
            </div>

            <div className="w-100 mt-10">
                <PostTable />
            </div>
        </div>
    );
};
export default ListPost;
