import React, { useState, useEffect } from 'react';
import './BookmarkApp.css';

const apiUrl = 'http://localhost:3000/api';

const BookmarkApp = () => {
  const [bookmarks, setBookmarks] = useState([]);
  const [newBookmark, setNewBookmark] = useState({ url: '', title: '' });
  const [searchQuery, setSearchQuery] = useState('');

  useEffect(() => {
    fetchAllBookmarks();
  }, []);

  const fetchAllBookmarks = async () => {
    try {
      const response = await fetch(`${apiUrl}/readAll.php`);
      const bookmarks = await response.json();
      setBookmarks(bookmarks);
    } catch (e) {
      console.error(e);
    }
  };

  const addNewBookmark = async () => {
    if (newBookmark.url.trim() && newBookmark.title.trim()) {
      const options = {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(newBookmark),
      };
      await fetch(`${apiUrl}/create.php`, options);
      setNewBookmark({ url: '', title: '' });
      fetchAllBookmarks();
    }
  };

  const deleteBookmark = async (id) => {
    const options = {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id }),
    };
    await fetch(`${apiUrl}/delete.php`, options);
    fetchAllBookmarks();
  };

  const filteredBookmarks = bookmarks.filter((bookmark) =>
    bookmark.title.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const handleNewBookmarkChange = (e, field) => {
    setNewBookmark({ ...newBookmark, [field]: e.target.value });
  };

  return (
    <div id="content">
      <div>
        <input
          type="text"
          placeholder="Bookmark URL"
          value={newBookmark.url}
          onChange={(e) => handleNewBookmarkChange(e, 'url')}
        />
        <input
          type="text"
          placeholder="Bookmark Title"
          value={newBookmark.title}
          onChange={(e) => handleNewBookmarkChange(e, 'title')}
        />
        <button onClick={addNewBookmark}>Add Bookmark</button>
      </div>
      <div>
        <input
          type="text"
          placeholder="Search Bookmark"
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
        />
      </div>
      <ul>
        {filteredBookmarks.map((bookmark) => (
          <li key={bookmark.id}>
            <a href={bookmark.url} target="_blank" rel="noopener noreferrer">
              {bookmark.title}
            </a>
            <button onClick={() => deleteBookmark(bookmark.id)}>Delete</button>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default BookmarkApp;
