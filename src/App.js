import './App.css';
import {Routes,Route } from 'react-router-dom';
import Signup from './pages/Signup';
import Login from './pages/Login';
import WorkSpaceCreation from './pages/WorkSpaceCreation';

function App() {
  return (
  <>
    

  <Routes>
    <Route exact path='/' element={<Login/>} />
    <Route exact path='/signup' element={<Signup/>}/>
    <Route exact path='/signup/createworkspace' element={<WorkSpaceCreation/>}  />
  </Routes>
  

  </>
  );
}

export default App;
