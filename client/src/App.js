import React, { lazy } from 'react';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import './App.scss';

const ListPost = lazy(() => import('./pages/posts/index'));

export const App = () => {
  return (
      <BrowserRouter basename='/'>
        <Routes>
          <Route path='/posts' element={<ListPost />} />
        </Routes>
      </BrowserRouter>
  );
}
