import React from 'react';
import { Button } from 'element-react';

const ListPost = () => {
    return (
        <div className="container mx-auto my-10">
            <div className="flex justify-between items-center">
                <div className="text-2xl font-bold leading-5">List Post</div>
                <Button
                    type="primary"
                >
                    Add Post
                </Button>
            </div>

            <div className="w-100 mt-10">
            </div>
        </div>
    );
};
export default ListPost;
