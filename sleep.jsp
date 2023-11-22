<%
try{
  Thread.sleep(Long.parseLong(request.getParameter("millis")));
}catch(InterruptedException e){}
%>
