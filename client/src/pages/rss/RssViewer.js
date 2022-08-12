import React, { useEffect, useState } from "react";
import { Loading, MessageBox } from "element-react";
const { parse } = require("rss-to-json");
const dayjs = require("dayjs");

const RssViewer = () => {
  const [loading, setLoading] = useState(false);
  const [rssData, setRssData] = useState({
    title: "",
    items: [],
  });

  useEffect(() => {
    fetchRss("https://blog.ethereum.org/feed.xml");
  }, []);

  const fetchRss = async (url) => {
    try {
      setLoading(true);
      const rss = await parse(`${process.env.REACT_APP_CORS_URL}/${url}`);
      setRssData(rss);
    } catch (e) {
      console.error(e);
      await MessageBox.alert("Error", JSON.stringify(e));
    } finally {
      setLoading(false);
    }
  };

  const clickArticle = (item) => {
    MessageBox.confirm("Do you want to go to this article?", "Confirmation", {
      confirmButtonText: "OK",
      cancelButtonText: "Cancel",
      type: "info",
    }).then(() => {
      window.location = item.link;
    });
  };

  return (
    <>
      <Loading loading={loading}>
        <div className="m-5">{rssData.title}</div>
        {rssData.items.map((item, i) => {
          return (
            <div
              className="m-5 mb-10"
              key={i}
              onClick={clickArticle.bind(this, item)}
            >
              <h3 className="font-bold text-2xl">{item.title}</h3>
              <em>{dayjs(item.created).format()}</em>
              <div dangerouslySetInnerHTML={{ __html: item.description }} />
            </div>
          );
        })}
      </Loading>
    </>
  );
};

export default RssViewer;
