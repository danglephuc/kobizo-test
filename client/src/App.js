import React from 'react';
import {Navigate, Route, Routes} from 'react-router-dom';
import './App.scss';
import Navbar from "./components/Navbar";
import ListPost from "./pages/posts/ListPost";
import EditPost from "./pages/posts/EditPost";
import RssViewer from "./pages/rss/RssViewer";

export const App = () => {
  return (
      <>
          <Navbar />
          <div className="relative w-full">
              <Routes>
                  <Route path="/" element={<Navigate to="/posts" replace />} />
                  <Route path="/posts" element={<ListPost />} />
                  <Route path="/posts/:postId" element={<EditPost />}/>
                  <Route path="/rss" element={<RssViewer/>}/>
              </Routes>
          </div>
      </>
  );
}
