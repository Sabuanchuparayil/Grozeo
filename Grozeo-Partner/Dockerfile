FROM mcr.microsoft.com/dotnet/sdk:8.0-alpine AS build
WORKDIR /src

# Railway builds from repo root, so use full paths
COPY Grozeo-Partner/RetalineProAgent.Net8/RetalineProAgent.csproj RetalineProAgent.Net8/
COPY Grozeo-Partner/RetalineProAgent.Core/RetalineProAgent.Core.csproj RetalineProAgent.Core/
RUN dotnet restore RetalineProAgent.Net8/RetalineProAgent.csproj

COPY Grozeo-Partner/RetalineProAgent.Net8/ RetalineProAgent.Net8/
COPY Grozeo-Partner/RetalineProAgent.Core/ RetalineProAgent.Core/
RUN dotnet publish RetalineProAgent.Net8/RetalineProAgent.csproj -c Release -o /app/publish --no-restore

FROM mcr.microsoft.com/dotnet/aspnet:8.0-alpine AS runtime
WORKDIR /app

RUN apk add --no-cache curl

COPY --from=build /app/publish .

ENV ASPNETCORE_URLS=http://+:5001
ENV ASPNETCORE_ENVIRONMENT=Production
EXPOSE 5001

HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
    CMD curl -f http://localhost:5001/health || exit 1

ENTRYPOINT ["dotnet", "RetalineProAgent.dll"]
