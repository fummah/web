import PropTypes from 'prop-types';
import React,{useState,useEffect} from 'react';

import Fab from '@mui/material/Fab';
import Button from '@mui/material/Button';
import Tooltip from '@mui/material/Tooltip';
import ChatIcon from '@mui/icons-material/Chat';
import SendIcon from '@mui/icons-material/Send';
import CloseIcon from '@mui/icons-material/Close';
import Typography from '@mui/material/Typography';

import useAxiosFetch from 'src/hooks/use-axios';

import Error from 'src/components/response/error';
import Success from 'src/components/response/success';
import 'src/components/others/FloatingActionButton.css';


function FeedbackPopup({ onClose, onSend,isLoading,isError,data,statusCode }) {
    const [feedback, setFeedback] = useState('');
  
    const handleChange = (event) => {
      setFeedback(event.target.value);
    };
  
    const handleSend = () => {
      onSend(feedback);
      setFeedback('');
    };
  
    return (
      <div className="feedback-popup">
        <textarea
          className="feedback-textarea"
          placeholder="Type your feedback here..."
          value={feedback}
          onChange={handleChange}
        />
           {isLoading?<Typography>Please wait...</Typography>:null}
      {isError?<Error mymessage={data.message}/>:null}
      {data && !isError && statusCode === 200?<Success mymessage={data.message}/>:null}
          <Button variant="outlined" startIcon={<SendIcon />} onClick={handleSend} style={{marginRight:10}}>
        Send
      </Button>
      <Button variant="outlined" color="error" startIcon={<CloseIcon />} onClick={onClose}>
        Close
      </Button>      
      </div>
    );
  }

function FloatingActionButton() {
    const [showPopup, setShowPopup] = useState(false);
    const [postData, setPostData] = useState({});
    const [feedbackBtnClicked, setFeedbackBtnClicked] = useState(false);
    const [f1, setF1] = useState(false);

    const { isLoading,isError, data,statusCode } = useAxiosFetch('feedback','POST',postData,feedbackBtnClicked);
  
    useEffect(() => {
    if(feedbackBtnClicked)
    {
      setPostData({'feedbackTxt':f1});  
      setFeedbackBtnClicked(false);  
      
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [feedbackBtnClicked]); 
  

  const togglePopup = () => {
    setShowPopup(!showPopup);
  };

  const handleSendFeedback = (feedback) => {
    setFeedbackBtnClicked(true);
    setF1(feedback);
  };

  return (
    <>  
    <Fab color="primary" aria-label="add" className="fab" onClick={togglePopup}>
        <Tooltip title="Give us feedback">
        <ChatIcon />
        </Tooltip>
      </Fab>
      {showPopup && (
        <FeedbackPopup onClose={togglePopup} onSend={handleSendFeedback} isLoading={isLoading} isError={isError} data={data} statusCode={statusCode}/>
      )}
      </>
  );
}

export default FloatingActionButton;

FeedbackPopup.propTypes = {
    onClose : PropTypes.any,
    onSend: PropTypes.any,
    isLoading: PropTypes.any,
    isError: PropTypes.any,
    data: PropTypes.any,
    statusCode: PropTypes.any,
  };