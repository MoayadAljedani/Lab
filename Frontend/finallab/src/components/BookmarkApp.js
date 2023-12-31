import React, { useState, useEffect } from 'react';
import './BookmarkApp.css';
import { FacebookShareButton, TwitterShareButton,WhatsappShareButton } from 'react-share';
import { FaFacebookSquare } from "react-icons/fa";
import { FaTwitter } from "react-icons/fa";
import { FaWhatsapp } from "react-icons/fa";

const apiUrl = 'http://localhost:3000/api';

const BookmarkApp = () => {
  const [bookmarks, setBookmarks] = useState([]);
  const [newBookmark, setNewBookmark] = useState({ url: '', title: '' });
  const [searchQuery, setSearchQuery] = useState('');
  const [isEditing, setIsEditing] = useState(false);
  const [currentBookmarkId, setCurrentBookmarkId] = useState(null);

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

  const shareMessage = "Checkout my new bookmark!"; 

  
  const filteredBookmarks = bookmarks.filter((bookmark) =>
    bookmark.title.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const handleNewBookmarkChange = (e, field) => {
    setNewBookmark({ ...newBookmark, [field]: e.target.value });
  };
  const startEditing = (bookmark) => {
    setNewBookmark({ url: bookmark.url, title: bookmark.title });
    setCurrentBookmarkId(bookmark.id);
    setIsEditing(true);
  };

  const updateBookmark = async () => {
    if (newBookmark.url.trim() && newBookmark.title.trim() && currentBookmarkId) {
      const options = {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ...newBookmark, id: currentBookmarkId }),
      };
      try {
        const response = await fetch(`${apiUrl}/update.php`, options);
        const data = await response.json();
        alert(data.message); 
        setIsEditing(false);
        fetchAllBookmarks();
      } catch (e) {
        console.error(e);
      }
    }
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
            <button onClick={() => startEditing(bookmark)}>Edit</button>

            <button onClick={() => deleteBookmark(bookmark.id)}>Delete</button>
            
            <div className="social-sharing">
              <FacebookShareButton url={bookmark.url} quote={shareMessage}>
                <FaFacebookSquare />
              </FacebookShareButton>
              <TwitterShareButton url={bookmark.url} title={shareMessage}>
                <FaTwitter />
              </TwitterShareButton>
              <WhatsappShareButton url={bookmark.url} title={shareMessage}>
                <FaWhatsapp />
              </WhatsappShareButton>
            </div>
            
          </li>
          
        ))}
        
      </ul>
      {isEditing && (
        <div className="edit-form">
            <label>Edit URL</label>
          <input
            type="text"
            placeholder="Edit URL"
            value={newBookmark.url}
            onChange={(e) => handleNewBookmarkChange(e, 'url')}
          />
           <label>Edit Title</label>
          <input
            type="text"
            placeholder="Edit Title"
            value={newBookmark.title}
            onChange={(e) => handleNewBookmarkChange(e, 'title')}
          />
          <button className="confirm-edit-btn" onClick={updateBookmark}>Confirm Edit</button>
          <button onClick={() => setIsEditing(false)}>Cancel</button>
        </div>
      )}
    </div>
  );
};

export default BookmarkApp;
