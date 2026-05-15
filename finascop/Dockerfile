FROM mcr.microsoft.com/dotnet/sdk:8.0-alpine AS build
WORKDIR /src

COPY Finascop.sln .
COPY DataEntry/DataEntry.csproj DataEntry/
COPY LogDB/LogDB.csproj LogDB/
RUN dotnet restore

COPY . .
RUN dotnet publish DataEntry/DataEntry.csproj -c Release -o /app/publish --no-restore

FROM mcr.microsoft.com/dotnet/aspnet:8.0-alpine AS runtime
WORKDIR /app

RUN apk add --no-cache curl

COPY --from=build /app/publish .

ENV AzureWebJobsStorage=""
ENV FUNCTIONS_WORKER_RUNTIME=dotnet
EXPOSE 80

CMD ["dotnet", "DataEntry.dll"]
