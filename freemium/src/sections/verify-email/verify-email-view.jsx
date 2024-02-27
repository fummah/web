import { Alert } from 'antd';
import { useRef, useState, useEffect } from 'react';

import Box from '@mui/material/Box';
import Grid from '@mui/material/Grid';
import Link from '@mui/material/Link';
import Card from '@mui/material/Card';
import Stack from '@mui/material/Stack';
import Divider from '@mui/material/Divider';
import TextField from '@mui/material/TextField';
import Typography from '@mui/material/Typography';
import LoadingButton from '@mui/lab/LoadingButton';
import { alpha, useTheme } from '@mui/material/styles';

import useAxiosFetch from 'src/hooks/use-axios';

import { bgGradient } from 'src/theme/css';

import Logo from 'src/components/logo';
import Error from 'src/components/response/error';
import Success from 'src/components/response/success';

// ----------------------------------------------------------------------

export default function VerifyEmailView() {
  const theme = useTheme();

  const [postData, setPostData] = useState({});
  const [verified, setVerified] = useState(false);
  const [verifypBtnClicked, setVerifyBtnClicked] = useState(false);
  const [enable] = useState(false);
  const [disable] = useState(true);

    const valueTempCode= useRef('');

   const { isLoading: isLoadingVerify, isError: isErrorVerify, data: dataVerify,statusCode: statusCodeVerify } = useAxiosFetch('verify-email','POST',postData,verifypBtnClicked);
  
    useEffect(() => {
    if(verifypBtnClicked)
    {
      setPostData(getFormValues());  
      setVerifyBtnClicked(false); 
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [verifypBtnClicked]); 

    useEffect(() => {
       if(dataVerify)
    {
    if(dataVerify.verified!==undefined)
      {
       setVerified(true);
      }
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [dataVerify]); 


    if(verified && statusCodeVerify === 200)
    {
      localStorage.removeItem('UNACTIVATEDID');
    }

    

  const handleVerifyEmail = (e) =>{
 setVerifyBtnClicked(true);
 e.preventDefault();
    };

    const getFormValues = () =>{
      const obj = {
        "temp_code":valueTempCode.current.value,
        "user_id":localStorage.getItem("UNACTIVATEDID"),
              };
      return obj;
    }

  const renderForm = (
    <>
      <Stack spacing={3}>
<Box component="form">

      <Grid container spacing={2}>
              <Grid item xs={12} >
                <TextField
                  autoComplete="given-name"
                  name="temp_code"
                  required
                  fullWidth
                  id="temp_code"
                  label="Temporary code"
                  autoFocus
                  inputRef={valueTempCode}
                />
              </Grid>
             
              </Grid>
           
              </Box>
       
      </Stack>
  <Divider>-</Divider>
      <LoadingButton
        fullWidth
        size="large"
        type="submit"
        variant="contained"
        color="inherit"
        style={{marginBottom:'10px'}}
        onClick={handleVerifyEmail}
        disabled={statusCodeVerify === 200 || isLoadingVerify?disable:enable}
      >
        Verify Now
      </LoadingButton>
      {isLoadingVerify?<Typography>Please wait...</Typography>:null}
      {isErrorVerify?<Error mymessage={dataVerify.message}/>:null}
      {dataVerify && !isErrorVerify?<Success mymessage={dataVerify.message}/>:null}
      {verified?  <Link variant="subtitle2" href="/login" sx={{ ml: 0.5 }}> Sign in  </Link>:null}
    </>
  );

  return (
    <Box
      sx={{
        ...bgGradient({
          color: alpha(theme.palette.background.default, 0.9),
          imgUrl: '/assets/background/overlay_4.jpg',
        }),
        height: 1,
      }}
    >
     
      <Stack alignItems="center" justifyContent="center" sx={{ height: 1 }}>
        <Card
          sx={{
            p: 5,
            width: 1,
            maxWidth: 600,
          }}
        >
         <Logo
        sx={{
                  top: { xs: 16, md: 24 },
          left: { xs: 16, md: 24 },
          height: 100,
        }}
      />
         
          <Typography variant="body2" sx={{ mt: 2, mb: 5 }}>
          <Alert
      message="Email Address Verification"
      description="Thank you for choosing security and protection! To complete the verification process, please enter the unique code sent to your registered email. This extra layer of verification ensures the safety of your account."
      type="success"
    />
                 </Typography>

          {renderForm}
        </Card>
      </Stack>
    </Box>
  );
}
