let obj = {
  displayName: "",
  email: "",
  photoURL: "",
  'user':{},
};
if(localStorage.getItem("USER") !== null){
  const user = JSON.parse(localStorage.getItem('USER'));
  obj = {
    displayName: `${user.first_name} ${user.last_name}`,
  email: user.email,
  photoURL: '/assets/images/avatars/avatar_25.jpg',
  'user':user,
  };
}
export const account = obj;
