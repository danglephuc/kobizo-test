import React, { useEffect, useState } from "react";
import { Loading, Table, Pagination, Button } from "element-react";
import { getListPosts } from "../../services/posts";
import { useNavigate } from "react-router-dom";

export const PostTable = () => {
  const [loading, setLoading] = useState(false);
  const [posts, setPosts] = useState([]);
  const [pagination, setPagination] = useState({
    current: 1,
    limit: 10,
    total: 0,
  });
  const navigate = useNavigate();

  useEffect(() => {
    fetchPosts(pagination.current);
  }, []);

  const fetchPosts = async (page) => {
    setLoading(true);
    try {
      const res = await getListPosts(page, pagination.limit);
      if (res.status) {
        setPosts(res.data.results);
        setPagination(res.data.pagination);
      } else {
        // TODO: show error
      }
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  const editPost = (post) => {
    navigate(`/posts/${post.id}`);
  };

  const onPageSizeChanged = (pageSize) => {
    setPagination({
      ...pagination,
      limit: pageSize,
    });
    fetchPosts(1);
  };

  const onPageChanged = (pageNumber) => {
    fetchPosts(pageNumber);
  };

  const columns = [
    {
      label: "ID",
      prop: "id",
    },
    {
      label: "Title",
      prop: "title",
    },
    {
      label: "Content",
      prop: "content",
    },
    {
      label: "Status",
      render: (row) => {
        return <div>{row.status === 1 ? "Active" : "Inactive"}</div>;
      },
    },
    {
      label: "Meta",
      render: (row) => {
        return (
          <div className="flex flex-col">
            {row.meta.map(function (meta, i) {
              return (
                <div key={i}>
                  {meta.key} - {meta.value}
                </div>
              );
            })}
          </div>
        );
      },
    },
    {
      label: "Action",
      align: "center",
      render: (row) => {
        return (
          <div>
            <Button
              className="btn-icon"
              type="warning"
              size="small"
              icon="edit"
              onClick={editPost.bind(this, row)}
            />
          </div>
        );
      },
    },
  ];

  return (
    <Loading loading={loading}>
      <Table
        className="w-100"
        emptyText="No Data."
        columns={columns}
        data={posts}
      />
      <Pagination
        className="mt-5"
        layout="total, sizes, prev, pager, next"
        total={pagination.total}
        pageSizes={[5, 10, 20, 50, 100]}
        pageSize={pagination.limit}
        currentPage={pagination.current}
        onSizeChange={(value) => {
          onPageSizeChanged(value);
        }}
        onCurrentChange={(value) => {
          onPageChanged(value);
        }}
      />
    </Loading>
  );
};
