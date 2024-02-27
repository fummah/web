import { useState,useEffect } from 'react';

import Box from '@mui/material/Box';
import Grid from '@mui/material/Grid';
import Stack from '@mui/material/Stack';
import Select from '@mui/material/Select';
import Divider from '@mui/material/Divider';
import MenuItem from '@mui/material/MenuItem';
import TextField from '@mui/material/TextField';
import Container from '@mui/material/Container';
import InputLabel from '@mui/material/InputLabel';
import Typography from '@mui/material/Typography';
import LoadingButton from '@mui/lab/LoadingButton';
import FormControl from '@mui/material/FormControl';

import useAxiosFetch from 'src/hooks/use-axios';

import Error from 'src/components/response/error';
import Loader from 'src/components/response/loader';
import Success from 'src/components/response/success';

// ----------------------------------------------------------------------

export default function ProfilePage() {
  const [firstName, setFirstName] = useState('-');
  const [lastName, setLastName] = useState('-');
  const [email, setEmail] = useState('-');
  const [idNumber, setIdNumber] = useState('-');
  const [medicalSchemeNumber, setMedicalSchemeNumber] = useState('-');
  const [schemes, setSchemes] = useState([]);
  const [updateBtnClicked, setUpdateBtnClicked] = useState(false);
  const [postData, setPostData] = useState({});
 const [medical_scheme, setMedicaScheme] = useState('');

 const { isLoading: isLoadingProfile, isError: isErrorProfile, data: dataProfile,statusCode:statusCodeProfile } = useAxiosFetch('getuser');
 const { isLoading: isLoadingUpdateProfile, isError: isErrorUpdateProfile, data: dataUpdateProfile,statusCode: statusCodeUpdateProfile } = useAxiosFetch('updateprofile','POST',postData,updateBtnClicked);

useEffect(() => {
 
    if(dataProfile && statusCodeProfile===200)
    {
      setFirstName(dataProfile.user.first_name);
      setLastName(dataProfile.user.last_name);
      setEmail(dataProfile.user.email);
      setIdNumber(dataProfile.user.id_number);
      setMedicalSchemeNumber(dataProfile.user.scheme_number);
      setSchemes(dataProfile.schemes);
      setMedicaScheme(dataProfile.user.scheme_name);
    }
      // eslint-disable-next-line react-hooks/exhaustive-deps
  },[dataProfile]);

  useEffect(() => {
    if(updateBtnClicked)
    {
      setPostData(getFormValues());       
      setUpdateBtnClicked(false);       
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [updateBtnClicked]);

  const handleUpdateBtn = (e) =>{
    setUpdateBtnClicked(true);
 e.preventDefault();
    };

  const handleChange = (event) => {
    setMedicaScheme(event.target.value);
  };
  const handleFirstName = (event) => {
    setFirstName(event.target.value);
  };
  const handleLastName = (event) => {
    setLastName(event.target.value);
  };
  const handleIdNumber = (event) => {
    setIdNumber(event.target.value);
  };
  const handleSchemeNumber = (event) => {
    setMedicalSchemeNumber(event.target.value);
  };
  const getFormValues = () =>{
    const obj = {
      "first_name":firstName,
      "last_name":lastName,
      "email":email,
      "id_number":idNumber,
      "scheme_number":medicalSchemeNumber,      
      "scheme_name":medical_scheme
    };
    return obj;
  }
  return (
    <Container>
     <Stack spacing={3}>
      <Typography variant="h4">My Profile</Typography>
<Box component="form">
{isLoadingProfile?<Loader/>:null}
{isErrorProfile?<Error mymessage={dataProfile.message}/>:null}
      <Grid container spacing={2}>
              <Grid item xs={12} sm={6}>
                <TextField
                  autoComplete="given-name"
                  name="firstName"
                  onChange={handleFirstName}
                  required
                  fullWidth
                  id="firstName"
                  value={firstName}
                  label="First Name"
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  required
                  fullWidth
                  id="lastName"
                  label="Last Name"
                  onChange={handleLastName}
                  name="lastName"
                  value={ lastName}
                  autoComplete="family-name"
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  required
                  fullWidth
                  id="email"
                  label="Email Address"
                  name="email"
                  value={ email}
                  autoComplete="email"
                />
              </Grid>
               <Grid item xs={12} sm={6}>
                <TextField
                  required
                  fullWidth
                  id="id-number"
                  label="ID Number"
                  name="id-number"
                  value={ idNumber}
                  onChange={handleIdNumber}
                  autoComplete="id-number"
                />
              </Grid>
                 <Grid item xs={12} sm={6}>
                 <FormControl sx={{ m: 1, minWidth: "100%" }}>
                  <InputLabel id="demo-select-small-label">Medical Scheme</InputLabel>
                   <Select
        labelId="demo-select-small-label"
        id="medical-scheme"
        value={medical_scheme}
        label="Medical Scheme"
        onChange={handleChange}
      >
       <MenuItem value={medical_scheme}>{medical_scheme}</MenuItem>
{schemes.length ? schemes.map((scheme,index)=>(
        <MenuItem value={scheme} key={index}>{scheme}</MenuItem>
       )):null}
        
      </Select>
          </FormControl>
              </Grid>
               <Grid item xs={12} sm={6}>
                <TextField
                  required
                  fullWidth
                  id="scheme-number"
                  onChange={handleSchemeNumber}
                  label="Scheme Number"
                  name="scheme-number"
                  value={ medicalSchemeNumber}
                  autoComplete="scheme-number"
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
        onClick={handleUpdateBtn}
      >
        Update Profile
      </LoadingButton>
      {isLoadingUpdateProfile?<Typography>Please wait...</Typography>:null}
      {isErrorUpdateProfile?<Error mymessage={dataUpdateProfile.message}/>:null}
      {dataUpdateProfile && !isErrorUpdateProfile && statusCodeUpdateProfile === 200?<Success mymessage={dataUpdateProfile.message}/>:null}
    </Container>
  );
}
