import React from "react";
import { Menu } from "element-react";
import { useEffect, useState } from "react";
import { useNavigate, useLocation } from "react-router-dom";

export default function Navbar() {
  const [currentIndex, setCurrentIndex] = useState("posts");
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    if (location.pathname.startsWith("/posts")) {
      setCurrentIndex("posts");
    } else if (location.pathname.startsWith("/rss")) {
      setCurrentIndex("rss");
    }
  }, []);

  const onSelect = (index) => {
    if (index !== currentIndex) {
      setCurrentIndex(index);
      if (index === "posts") {
        navigate(`/posts`);
      } else if (index === "rss") {
        navigate(`/rss`);
      }
    }
  };

  return (
    <>
      <div>
        <Menu
          defaultActive={currentIndex}
          className="el-menu-demo"
          mode="horizontal"
          onSelect={onSelect}
        >
          <Menu.Item index="posts">Posts</Menu.Item>
          <Menu.Item index="rss">RSS</Menu.Item>
        </Menu>
      </div>
    </>
  );
}
